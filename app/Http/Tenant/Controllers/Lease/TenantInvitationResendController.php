<?php

namespace Smartville\Http\Tenant\Controllers\Lease;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Leases\Models\Lease;
use Smartville\Domain\Properties\Events\Tenant\Invitations\ResendTenantInvitation;

class TenantInvitationResendController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Lease $tenant
     * @return \Illuminate\Http\Response
     */
    public function resendInvitationEmail(Request $request, Lease $tenant)
    {
        if (Gate::denies('create lease')) {
            return redirect()->route('tenant.dashboard');
        }

        // property
        $property = $tenant->property;

        // invitation
        $invitation = $tenant->invitation;

        // call event to resend invitation
        event(new ResendTenantInvitation($property, $invitation));

        return back()->withSuccess("Activation email has been sent to {$invitation->name}.");
    }
}
