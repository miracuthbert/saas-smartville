<?php

namespace Smartville\Http\Account\Controllers\Invoice;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Utilities\Models\UtilityInvoice;

class UtilityInvoicePreviewController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Utilities\Models\UtilityInvoice $utilityInvoice
     * @return \Illuminate\Http\Response
     */
    public function preview(UtilityInvoice $utilityInvoice)
    {
        if (Gate::denies('utilityInvoice.view', $utilityInvoice)) {
            abort(404);
        }

        $utilityInvoice->load('utility', 'property.company', 'user', 'payments.invoice');

        $pdf = PDF::loadView('invoices.utilities.pdf.default', compact('utilityInvoice'));

        return $pdf->stream();
    }
}
