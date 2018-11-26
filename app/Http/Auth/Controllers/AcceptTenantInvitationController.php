<?php

namespace Smartville\Http\Auth\Controllers;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Properties\Models\Property;
use Smartville\Domain\Users\Models\UserInvitation;

class AcceptTenantInvitationController extends Controller
{
    public function setupTenantInvitation(Property $property, UserInvitation $invitation)
    {
        request()->session()->put('tenant_invitation', [
            'property' => $property->id,
            'invitation' => $invitation->id,
        ]);

        $names = explode(" ", $invitation->name);
        $firstName = array_first($names);
        $lastName = count($names) > 1 ? array_last($names) : null;
        $username = strtolower(implode(".", $names));

        return redirect()->route('register')->withInput([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => $username,
            'email' => $invitation->email
        ])->withInfo('Complete your registration to access your tenant dashboard.');
    }
}
