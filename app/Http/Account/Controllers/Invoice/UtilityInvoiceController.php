<?php

namespace Smartville\Http\Account\Controllers\Invoice;

use Illuminate\Support\Facades\Gate;
use Smartville\Domain\Utilities\Models\UtilityInvoice;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;

class UtilityInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $invoices = $request->user()->utilityInvoices()
            ->with(['property', 'utility.company', 'payments'])
            ->latest()
            ->paginate();

        return view('account.invoices.utilities.index', compact('invoices'));
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
     * @param  \Smartville\Domain\Utilities\Models\UtilityInvoice $utilityInvoice
     * @return \Illuminate\Http\Response
     */
    public function show(UtilityInvoice $utilityInvoice)
    {
        if (Gate::denies('utilityInvoice.view', $utilityInvoice)) {
            abort(404);
        }

        $utilityInvoice->load('utility', 'property.company', 'payments.invoice', 'payments.admin');

        return view('account.invoices.utilities.show', compact('utilityInvoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Smartville\Domain\Utilities\Models\UtilityInvoice $utilityInvoice
     * @return \Illuminate\Http\Response
     */
    public function edit(UtilityInvoice $utilityInvoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Smartville\Domain\Utilities\Models\UtilityInvoice $utilityInvoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UtilityInvoice $utilityInvoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Utilities\Models\UtilityInvoice $utilityInvoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(UtilityInvoice $utilityInvoice)
    {
        //
    }
}
