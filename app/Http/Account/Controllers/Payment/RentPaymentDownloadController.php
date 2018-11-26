<?php

namespace Smartville\Http\Account\Controllers\Payment;

use PDF;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Leases\Models\LeasePayment;

class RentPaymentDownloadController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param LeasePayment $leasePayment
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf(LeasePayment $leasePayment)
    {
        if (Gate::denies('leasePayment.view', $leasePayment)) {
            abort(404);
        }

        $leasePayment->load('property', 'lease', 'invoice', 'admin');

        $pdf = PDF::loadView('payments.rent.pdf', compact('leasePayment'));

        return $pdf->download("{$leasePayment->hash_id}.pdf");
    }
}
