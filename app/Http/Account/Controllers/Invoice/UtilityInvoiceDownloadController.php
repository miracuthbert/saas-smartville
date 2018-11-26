<?php

namespace Smartville\Http\Account\Controllers\Invoice;

use PDF;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Utilities\Models\UtilityInvoice;

class UtilityInvoiceDownloadController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Utilities\Models\UtilityInvoice $utilityInvoice
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf(UtilityInvoice $utilityInvoice)
    {
        if (Gate::denies('utilityInvoice.view', $utilityInvoice)) {
            abort(404);
        }

        $utilityInvoice->load('utility', 'property.company', 'user', 'payments.invoice');

        $pdf = PDF::loadView('invoices.utilities.pdf.default', compact('utilityInvoice'));

        return $pdf->download(
            "{$utilityInvoice->hash_id} - {$utilityInvoice->utility->name} - {$utilityInvoice->formattedInvoiceMonth}.pdf"
        );
    }
}
