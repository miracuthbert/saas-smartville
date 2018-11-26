<?php

namespace Smartville\Http\Account\Controllers\Invoice;

use Illuminate\Support\Facades\Gate;
use Smartville\Domain\Leases\Models\LeaseInvoice;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;

class RentInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $invoices = $request->user()->rentInvoices()
            ->with('property', 'payments')
            ->latest()
            ->paginate();

        return view('account.invoices.rent.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Leases\Models\LeaseInvoice $leaseInvoice
     * @return \Illuminate\Http\Response
     */
    public function show(LeaseInvoice $leaseInvoice)
    {
        if (Gate::denies('leaseInvoice.view', $leaseInvoice)) {
            abort(404);
        }

        $leaseInvoice->load('property', 'lease', 'payments.invoice', 'payments.admin');

        return view('account.invoices.rent.show', compact('leaseInvoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Smartville\Domain\Leases\Models\LeaseInvoice $leaseInvoice
     * @return \Illuminate\Http\Response
     */
    public function edit(LeaseInvoice $leaseInvoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Smartville\Domain\Leases\Models\LeaseInvoice $leaseInvoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeaseInvoice $leaseInvoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Leases\Models\LeaseInvoice $leaseInvoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeaseInvoice $leaseInvoice)
    {
        //
    }
}
