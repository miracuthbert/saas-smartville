<?php

namespace Smartville\Http\Tenant\Controllers\Rent;

use PDF;
use Illuminate\Support\Facades\Gate;
use Smartville\Domain\Leases\Models\LeasePayment;
use Smartville\Domain\Leases\Models\LeaseInvoice;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;

class RentInvoicePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Smartville\Domain\Leases\Models\LeaseInvoice $leaseInvoice
     * @return \Illuminate\Http\Response
     */
    public function index(LeaseInvoice $leaseInvoice)
    {
        $payments = $leaseInvoice->payments()
            ->with('invoice', 'admin')
            ->orderByDesc('paid_at')
            ->get();

        return view('tenant.rent.payments.index', compact('payments', 'leaseInvoice'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Leases\Models\LeaseInvoice $leaseInvoice
     * @param  \Smartville\Domain\Leases\Models\LeasePayment $leasePayment
     * @return \Illuminate\Http\Response
     */
    public function show(LeaseInvoice $leaseInvoice, LeasePayment $leasePayment)
    {
        if (Gate::denies('leasePayment.view', $leasePayment)) {
            abort(404);
        }

        $leasePayment->load('property', 'lease', 'invoice', 'admin');

        $pdf = PDF::loadView('payments.rent.pdf', compact('leasePayment'));

        return $pdf->stream();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Smartville\Domain\Leases\Models\LeaseInvoice $leaseInvoice
     * @param  \Smartville\Domain\Leases\Models\LeasePayment $leasePayment
     * @return \Illuminate\Http\Response
     */
    public function edit(LeaseInvoice $leaseInvoice, LeasePayment $leasePayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Smartville\Domain\Leases\Models\LeaseInvoice $leaseInvoice
     * @param  \Smartville\Domain\Leases\Models\LeasePayment $leasePayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeaseInvoice $leaseInvoice, LeasePayment $leasePayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Leases\Models\LeaseInvoice $leaseInvoice
     * @param  \Smartville\Domain\Leases\Models\LeasePayment $leasePayment
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(LeaseInvoice $leaseInvoice, LeasePayment $leasePayment)
    {
        try {
            // delete payment
            $leasePayment->delete();

            // update invoice status
            $leaseInvoice->update([
                'cleared_at' => null,
            ]);
        } catch (\Exception $e) {
            return back()->withSuccess("Some error occurred when trying to revoke payment. Please try again!");
        }

        cache()->forget('rentInvoices');

        return back()->withSuccess("Payment revoked. Invoice status updated.");
    }
}
