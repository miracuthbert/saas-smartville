<?php

namespace Smartville\Http\Tenant\Controllers\Property;

use Illuminate\Support\Facades\Gate;
use Smartville\Domain\Properties\Events\ExistingUserTenantInvitation;
use Smartville\Domain\Properties\Events\UnregistedUserTenantInvitation;
use Smartville\Domain\Users\Models\User;
use Smartville\Domain\Users\Models\UserInvitation;
use Smartville\Domain\Properties\Models\Property;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Http\Tenant\Requests\TenantStoreRequest;

class TenantInvitationController extends Controller
{
    /**
     * TenantInvitationController constructor.
     */
    public function __construct()
    {
        $this->middleware('property.occupied')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Smartville\Domain\Properties\Models\Property $property
     * @return \Illuminate\Http\Response
     */
    public function index(Property $property)
    {
        if (Gate::denies('create lease')) {
            return redirect()->route('tenant.dashboard');
        }

        return view('tenant.properties.invitations.index', $property);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Smartville\Domain\Properties\Models\Property $property
     * @return \Illuminate\Http\Response
     */
    public function create(Property $property)
    {
        if (Gate::denies('create lease')) {
            return redirect()->route('tenant.dashboard');
        }

        return view('tenant.properties.invitations.create', compact('property'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TenantStoreRequest $request
     * @param  \Smartville\Domain\Properties\Models\Property $property
     * @return \Illuminate\Http\Response
     */
    public function store(TenantStoreRequest $request, Property $property)
    {
        if (Gate::denies('create lease')) {
            return redirect()->route('tenant.dashboard');
        }

        $email = $request->email;
        $name = $request->name;
        $movesIn = $request->moved_in_at;
        $movesOut = $request->moved_out_at;

        $company = $property->company;

        $user = User::where('email', $request->email)->first();

        if ($user && $user->exists()) {
            // call event to send invitation
            event(new ExistingUserTenantInvitation($property, $user, $movesIn, $movesOut));

            // set property status as occupied
            $property->updatePropertyOccupancyStatus();

            return redirect()->route('tenant.properties.index')
                ->withSuccess("Tenant added to {$property->name}.");
        }

        // create invitation
        $invitation = $company->generateInvitationToken($email, $name, "company_tenant_invitation");

        // call event to send invitation
        event(new UnregistedUserTenantInvitation($property, $invitation, $movesIn, $movesOut));

        return redirect()->route('tenant.properties.index')
            ->withSuccess("Tenant added to {$property->name}. Once they sign up their details will be updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Properties\Models\Property $property
     * @param  \Smartville\Domain\Users\Models\UserInvitation $userInvitation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Property $property, UserInvitation $userInvitation)
    {
        //
    }
}
