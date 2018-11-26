<?php

namespace Smartville\Http\Tenant\Controllers\Lease;

use Illuminate\Support\Facades\Gate;
use Smartville\Domain\Leases\Models\Lease;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Properties\Events\ExistingUserTenantInvitation;
use Smartville\Domain\Properties\Events\UnregistedUserTenantInvitation;
use Smartville\Domain\Properties\Models\Property;
use Smartville\Domain\Users\Models\User;
use Smartville\Http\Tenant\Requests\TenantStoreRequest;
use Smartville\Http\Tenant\Requests\TenantUpdateRequest;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Gate::denies('browse leases')) {
            return redirect()->route('tenant.dashboard');
        }

        $tenants = $request->tenant()->leases()->with('user', 'property', 'invitation')->latest()->paginate();

        return view('tenant.tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('create lease')) {
            return redirect()->route('tenant.dashboard');
        }

        $properties = Property::finished()->vacant()->get();

        return view('tenant.tenants.create', compact('properties'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TenantStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TenantStoreRequest $request)
    {
        if (Gate::denies('create lease')) {
            return redirect()->route('tenant.dashboard');
        }

        $email = $request->email;
        $name = $request->name;
        $propertyId = $request->property_id;
        $movesIn = $request->moved_in_at;
        $movesOut = $request->moved_out_at;

        $property = Property::find($propertyId);
        $property->load('currentLease.user', 'currentLease.invitation');

        // redirect if property has tenant
        if (($status = $property->hasActiveTenant())) {
            return redirect()->route('tenant.tenants.index')
                ->withWarning("{$property->name} has an active tenant. Please choose another property to add tenant.");
        }

        $company = $property->company;

        $user = User::where('email', $request->email)->first();

        if ($user && $user->exists()) {
            // call event to send invitation
            event(new ExistingUserTenantInvitation($property, $user, $movesIn, $movesOut));

            // set property status as occupied
            $property->updatePropertyOccupancyStatus();

            return redirect()->route('tenant.tenants.index')
                ->withSuccess("{$user->name} added to {$property->name}.");
        }

        // create invitation
        $invitation = $company->generateInvitationToken($email, $name, "company_tenant_invitation");

        // call event to send invitation
        event(new UnregistedUserTenantInvitation($property, $invitation, $movesIn, $movesOut));

        return redirect()->route('tenant.tenants.index')
            ->withSuccess("{$name} added to {$property->name}. Once they sign up their details will be updated.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Leases\Models\Lease $lease
     * @return \Illuminate\Http\Response
     */
    public function show(Lease $lease)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Smartville\Domain\Leases\Models\Lease $lease
     * @return \Illuminate\Http\Response
     */
    public function edit(Lease $lease)
    {
        if (Gate::denies('update lease')) {
            return redirect()->route('tenant.dashboard');
        }

        $lease->load('property', 'user', 'invitation');

        return view('tenant.tenants.edit', compact('lease'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TenantUpdateRequest $request
     * @param  \Smartville\Domain\Leases\Models\Lease $lease
     * @return \Illuminate\Http\Response
     */
    public function update(TenantUpdateRequest $request, Lease $lease)
    {
        if (Gate::denies('update lease')) {
            return redirect()->route('tenant.dashboard');
        }

        $lease->fill($request->only('moved_in_at', 'moved_out_at'));
        $lease->save();

        return back()->withSuccess("Tenant lease updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Leases\Models\Lease $lease
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Lease $lease)
    {
        if (Gate::denies('delete lease')) {
            return redirect()->route('tenant.dashboard');
        }

        $name = optional($lease->user)->name ?: $lease->invitation->name;
        $property = $lease->property;

        try {
            $lease->delete();

            $property->updatePropertyToVacantStatus();
        } catch (\Exception $e) {
            return back()->withError("Failed deleting {$name} lease for {$property->name}.");
        }

        return back()->withSuccess("{$name} lease for {$property->name} deleted successfully.");
    }
}
