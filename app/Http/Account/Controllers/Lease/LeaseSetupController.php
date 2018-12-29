<?php

namespace Smartville\Http\Account\Controllers\Lease;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Properties\Models\Property;
use Smartville\Domain\Users\Models\UserInvitation;

class LeaseSetupController extends Controller
{
    /**
     * Resumes user's tenant invitation setup.
     *
     * @param Request $request
     * @param Property $property
     * @param UserInvitation $invitation
     * @return \Illuminate\Http\Response
     */
    public function resumeLeaseSetup(Request $request, Property $property, UserInvitation $invitation)
    {
        $confirm = $request->user()->resumeTenantInvitation($property->id, $invitation->id);

        if (!$confirm) {
            return back()->withError("Failed setting up lease for {$property->name}.")
                ->with('error_link', route('account.leases.setup.resume', [$property, $invitation]))
                ->with('alert_link_name', 'Try setup again');
        }

        return redirect()->route('tenant.switch', [$property->company, 'redirect' => 'tenants'])
            ->withSuccess("Your lease has been successfully added. Check your list of leases to see the changes.");
    }
}
