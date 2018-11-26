<?php

namespace Smartville\Http\Api\Controllers\Auth;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Auth\Events\UserRequestedActivationEmail;
use Smartville\Http\Auth\Requests\ActivateResendRequest;
use Smartville\Domain\Users\Models\User;

class ActivationResendController extends Controller
{

    /**
     * Resend activation link.
     *
     * @param ActivateResendRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ActivateResendRequest $request)
    {
        //find user
        $user = User::where('email', $request->email)->first();

        //send activation email
        event(new UserRequestedActivationEmail($user));

        return response()->json(null, 204);
    }
}
