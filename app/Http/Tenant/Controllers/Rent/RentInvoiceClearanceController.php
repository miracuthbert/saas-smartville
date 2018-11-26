<?php

namespace Smartville\Http\Tenant\Controllers\Rent;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Leases\Models\LeaseInvoice;
use Smartville\Domain\Leases\Models\LeasePayment;
use Smartville\Domain\Leases\Notifications\RentInvoiceCleared;
use Smartville\Domain\Leases\Notifications\RentInvoicePaid;
use Smartville\Http\Tenant\Requests\LeaseInvoiceClearanceStoreRequest;

class RentInvoiceClearanceController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Leases\Models\LeaseInvoice $leaseInvoice
     * @return \Illuminate\Http\Response
     */
    public function index(LeaseInvoice $leaseInvoice)
    {
        if (Carbon::now()->lt($leaseInvoice->sent_at)) {
            return redirect()->route('tenant.rent.invoices.edit', $leaseInvoice);
        }

        if ($leaseInvoice->cleared_at) {
            return redirect()->route('tenant.rent.invoices.show', $leaseInvoice);
        }

        $leaseInvoice->load('property', 'lease', 'user', 'payments');

        return view('tenant.rent.clearance.index', compact('leaseInvoice'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LeaseInvoiceClearanceStoreRequest $request
     * @param  \Smartville\Domain\Leases\Models\LeaseInvoice $leaseInvoice
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(LeaseInvoiceClearanceStoreRequest $request, LeaseInvoice $leaseInvoice)
    {
        $payment = new LeasePayment();
        $payment->fill($request->only('amount', 'description', 'paid_at'));
        $payment->invoice()->associate($leaseInvoice);
        $payment->lease()->associate($leaseInvoice->lease);
        $payment->property()->associate($leaseInvoice->lease->property);
        $payment->admin()->associate($request->user());
        $payment->save();

        if ($leaseInvoice->paymentsTotal() >= $leaseInvoice->initialAmount) {

            // clear invoice
            $leaseInvoice->update([
                'cleared_at' => Carbon::now()
            ]);

            // optional: call (RentInvoiceCleared) event to notify admin
            // send user invoice clearance notification
            $leaseInvoice->user->notify(new RentInvoiceCleared($payment));

            return redirect()->route('tenant.rent.invoices.show', $leaseInvoice)
                ->withSuccess("Invoice balance cleared. Tenant will be notified.");
        }

        // optional: call (RentInvoicePaid) event to notify admin
        // send user invoice payment received notification
        $leaseInvoice->user->notify((new RentInvoicePaid($payment))->delay(now()->addMinutes(3)));

        cache()->forget('rentInvoices');

        return redirect()->route('tenant.rent.invoices.show', $leaseInvoice)
            ->withSuccess("Invoice successfully updated. Tenant will be notified.")
            ->withInfo("Invoice will be cleared once remaining balance is accounted for.");
    }
}
