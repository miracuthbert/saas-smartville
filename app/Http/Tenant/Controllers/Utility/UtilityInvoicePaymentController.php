<?php

namespace Smartville\Http\Tenant\Controllers\Utility;

use Illuminate\Support\Facades\Gate;
use Smartville\Domain\Utilities\Models\UtilityPayment;
use Smartville\Domain\Utilities\Models\UtilityInvoice;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;

class UtilityInvoicePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Smartville\Domain\Utilities\Models\UtilityInvoice $utilityInvoice
     * @return \Illuminate\Http\Response
     */
    public function index(UtilityInvoice $utilityInvoice)
    {
        if (Gate::denies('utilityPayment.browse', UtilityPayment::class)) {
            return redirect()->route('tenant.dashboard');
        }

        $payments = $utilityInvoice->payments()
            ->with('invoice', 'admin')
            ->orderByDesc('paid_at')
            ->get();

        return view('tenant.utilities.invoices.payments.index', compact('payments', 'utilityInvoice'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Smartville\Domain\Utilities\Models\UtilityInvoice $utilityInvoice
     * @return \Illuminate\Http\Response
     */
    public function create(UtilityInvoice $utilityInvoice)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Smartville\Domain\Utilities\Models\UtilityInvoice $utilityInvoice
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, UtilityInvoice $utilityInvoice)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Utilities\Models\UtilityInvoice $utilityInvoice
     * @param  \Smartville\Domain\Utilities\Models\UtilityPayment $utilityPayment
     * @return \Illuminate\Http\Response
     */
    public function show(UtilityInvoice $utilityInvoice, UtilityPayment $utilityPayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Smartville\Domain\Utilities\Models\UtilityInvoice $utilityInvoice
     * @param  \Smartville\Domain\Utilities\Models\UtilityPayment $utilityPayment
     * @return \Illuminate\Http\Response
     */
    public function edit(UtilityInvoice $utilityInvoice, UtilityPayment $utilityPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Smartville\Domain\Utilities\Models\UtilityInvoice $utilityInvoice
     * @param  \Smartville\Domain\Utilities\Models\UtilityPayment $utilityPayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UtilityInvoice $utilityInvoice, UtilityPayment $utilityPayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Utilities\Models\UtilityInvoice $utilityInvoice
     * @param  \Smartville\Domain\Utilities\Models\UtilityPayment $utilityPayment
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(UtilityInvoice $utilityInvoice, UtilityPayment $utilityPayment)
    {
        if (Gate::denies('utilityPayment.delete', $utilityPayment)) {
            return redirect()->route('tenant.dashboard');
        }

        try {
            // delete payment
            $utilityPayment->delete();

            // update invoice status
            $utilityInvoice->update([
                'cleared_at' => null,
            ]);
        } catch (\Exception $e) {
            return back()->withSuccess("Some error occurred when trying to revoke payment. Please try again!");
        }

        cache()->forget('utilityInvoices');

        return back()->withSuccess("Payment revoked. Invoice status updated.");
    }
}
