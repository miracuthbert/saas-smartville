<?php

namespace Smartville\Http\Tenant\Controllers\Rent;

use PDF;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Leases\Models\LeaseInvoice;

class RentInvoicePreviewController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  LeaseInvoice $leaseInvoice
     * @return \Illuminate\Http\Response
     */
    public function index(LeaseInvoice $leaseInvoice)
    {
        $leaseInvoice->load('property', 'lease', 'user');

        $pdf = PDF::loadView('invoices.rent.pdf', compact('leaseInvoice'));

        return $pdf->stream();
    }
}
