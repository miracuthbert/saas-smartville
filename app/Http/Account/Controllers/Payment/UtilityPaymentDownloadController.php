<?php

namespace Smartville\Http\Account\Controllers\Payment;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Utilities\Models\UtilityPayment;

class UtilityPaymentDownloadController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Utilities\Models\UtilityPayment $utilityPayment
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf(UtilityPayment $utilityPayment)
    {
        if (Gate::denies('utilityPayment.view', $utilityPayment)) {
            abort(404);
        }

        $utilityPayment->load('property', 'utility', 'invoice', 'admin');

        $pdf = PDF::loadView('payments.utilities.pdf.default', compact('utilityPayment'));

        return $pdf->download("{$utilityPayment->hash_id} - {$utilityPayment->invoice->formattedInvoiceMonth}.pdf");
    }
}
