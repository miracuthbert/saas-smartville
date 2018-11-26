<?php

namespace Smartville\Http\Account\Controllers\Lease;

use Smartville\Domain\Leases\Models\Lease;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;

class LeaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $leases = $request->user()->leases()->with('property')->latest()->paginate();

        return view('account.leases.index', compact('leases'));
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
     * @param  \Smartville\Domain\Leases\Models\Lease $lease
     * @return \Illuminate\Http\Response
     */
    public function show(Lease $lease)
    {
        $lease->load('property',
            'rentInvoices',
            'rentInvoices.payments',
            'rentPayments.invoice',
            'rentPayments.admin'
        );

        return view('account.leases.show', compact('lease'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Smartville\Domain\Leases\Models\Lease $lease
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lease $lease)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Leases\Models\Lease $lease
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lease $lease)
    {
        //
    }
}
