<?php

namespace Smartville\Http\Account\Controllers\Payment;

use PDF;
use Illuminate\Support\Facades\Gate;
use Smartville\Domain\Leases\Models\LeasePayment;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;

class RentPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $payments = $request->user()->rentPayments()
            ->with('property', 'lease', 'invoice', 'admin')
            ->orderByDesc('paid_at')
            ->paginate();

        return view('account.payments.rent.index', compact('payments'));
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
     * @param  \Smartville\Domain\Leases\Models\LeasePayment $leasePayment
     * @return \Illuminate\Http\Response
     */
    public function show(LeasePayment $leasePayment)
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
     * @param  \Smartville\Domain\Leases\Models\LeasePayment $leasePayment
     * @return \Illuminate\Http\Response
     */
    public function edit(LeasePayment $leasePayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Smartville\Domain\Leases\Models\LeasePayment $leasePayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeasePayment $leasePayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Leases\Models\LeasePayment $leasePayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeasePayment $leasePayment)
    {
        //
    }
}
