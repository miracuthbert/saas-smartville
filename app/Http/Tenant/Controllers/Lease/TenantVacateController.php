<?php

namespace Smartville\Http\Tenant\Controllers\Lease;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Leases\Models\Lease;

class TenantVacateController extends Controller
{
    /**
     * Show the confirmation form for tenant vacancy the specified resource.
     *
     * @param  \Smartville\Domain\Leases\Models\Lease $lease
     * @return \Illuminate\Http\Response
     */
    public function index(Lease $lease)
    {
        if (Gate::denies('update lease')) {
            return redirect()->route('tenant.dashboard');
        }

        $lease->load('property', 'user');

        return view('tenant.tenants.vacate.index', compact('lease'));
    }

    /**
     * Update lease vacancy the specified resource in storage.
     *
     * @param Request $request
     * @param  \Smartville\Domain\Leases\Models\Lease $lease
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Lease $lease)
    {
        if (Gate::denies('update lease')) {
            return redirect()->route('tenant.dashboard');
        }

        $lease->vacateProperty();
        $lease->vacated_at = Carbon::parse($request->vacated_at) ?: Carbon::now();
        $lease->save();

        return back()->withSuccess("Tenant lease updated successfully.")
            ->withInfo("Tenant has now been vacated.");
    }
}
