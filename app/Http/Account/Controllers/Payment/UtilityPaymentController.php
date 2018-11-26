<?php

namespace Smartville\Http\Account\Controllers\Payment;

use Smartville\Domain\Utilities\Models\UtilityPayment;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;

class UtilityPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $payments = $request->user()->utilityPayments()
            ->with('property.company', 'utility', 'invoice', 'admin')
            ->orderByDesc('paid_at')
            ->paginate();

        return view('account.payments.utilities.index', compact('payments'));
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
     * @param  \Smartville\Domain\Utilities\Models\UtilityPayment $utilityPayment
     * @return \Illuminate\Http\Response
     */
    public function show(UtilityPayment $utilityPayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Smartville\Domain\Utilities\Models\UtilityPayment $utilityPayment
     * @return \Illuminate\Http\Response
     */
    public function edit(UtilityPayment $utilityPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Smartville\Domain\Utilities\Models\UtilityPayment $utilityPayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UtilityPayment $utilityPayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Utilities\Models\UtilityPayment $utilityPayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(UtilityPayment $utilityPayment)
    {
        //
    }
}
