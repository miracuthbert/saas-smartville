<?php

namespace Smartville\Http\Account\Controllers\Invoice;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Leases\Models\LeaseInvoice;

class RentInvoiceDownloadController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  LeaseInvoice $leaseInvoice
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf(LeaseInvoice $leaseInvoice)
    {
        if (Gate::denies('leaseInvoice.view', $leaseInvoice)) {
            abort(404);
        }

        $leaseInvoice->load('property', 'lease', 'user');

        $pdf = PDF::loadView('invoices.rent.pdf', compact('leaseInvoice'));

        return $pdf->download("{$leaseInvoice->hash_id}.pdf");
    }
}
