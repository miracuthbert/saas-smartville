<?php

namespace Smartville\Http\Tenant\Controllers\Utility;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Utilities\Models\UtilityInvoice;
use Smartville\Domain\Utilities\Models\UtilityPayment;
use Smartville\Domain\Utilities\Notifications\UtilityInvoiceCleared;
use Smartville\Domain\Utilities\Notifications\UtilityInvoicePaid;
use Smartville\Http\Tenant\Requests\UtilityInvoiceClearanceStoreRequest;

class UtilityInvoiceClearanceController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Utilities\Models\UtilityInvoice $utilityInvoice
     * @return \Illuminate\Http\Response
     */
    public function index(UtilityInvoice $utilityInvoice)
    {
        if (Gate::denies('utilityPayment.create', UtilityPayment::class)) {
            return redirect()->route('tenant.dashboard');
        }

        $utilityInvoice->load('utility', 'property', 'lease', 'user');

        return view('tenant.utilities.invoices.clearance.index', compact('utilityInvoice'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UtilityInvoiceClearanceStoreRequest $request
     * @param  \Smartville\Domain\Utilities\Models\UtilityInvoice $utilityInvoice
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(UtilityInvoiceClearanceStoreRequest $request, UtilityInvoice $utilityInvoice)
    {
        if (Gate::denies('utilityPayment.create', UtilityPayment::class)) {
            return redirect()->route('tenant.dashboard');
        }

        $payment = new UtilityPayment();
        $payment->fill($request->only('amount', 'description', 'paid_at'));
        $payment->utility()->associate($utilityInvoice->utility);
        $payment->invoice()->associate($utilityInvoice);
        $payment->lease()->associate($utilityInvoice->lease);
        $payment->property()->associate($utilityInvoice->property);
        $payment->admin()->associate($request->user());
        $payment->save();

        if ($utilityInvoice->paymentsTotal() >= $utilityInvoice->initialAmount) {
            $utilityInvoice->update([
                'cleared_at' => Carbon::now()
            ]);

            // optional: call (UtilityInvoiceCleared) event to notify admin
            // send user invoice clearance notification
            $utilityInvoice->user->notify((new UtilityInvoiceCleared($payment))->delay(now()->addMinutes(3)));

            return redirect()->route('tenant.utilities.invoices.show', $utilityInvoice)
                ->withSuccess("Invoice balance cleared. Tenant will be notified.");
        }

        // optional: call (UtilityInvoicePaid) event to notify admin
        // send user invoice payment received notification
        $utilityInvoice->user->notify((new UtilityInvoicePaid($payment))->delay(now()->addMinutes(3)));

        cache()->forget('utilityInvoices');

        return redirect()->route('tenant.utilities.invoices.show', $utilityInvoice)
            ->withSuccess("Invoice successfully updated. Tenant will be notified.")
            ->withInfo("Invoice will be cleared once remaining balance is accounted for.");
    }
}
