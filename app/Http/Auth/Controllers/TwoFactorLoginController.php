<?php

namespace Smartville\Http\Auth\Controllers;

use Illuminate\Support\Facades\Auth;
use Smartville\Http\Account\Requests\TwoFactor\TwoFactorVerifyRequest;
use Smartville\Domain\Users\Models\User;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;

class TwoFactorLoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/account/dashboard';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.twofactor.index');
    }

    /**
     * Verify token and login user.
     *
     * @param TwoFactorVerifyRequest $request
     * @return \Illuminate\Http\Response
     */
    public function verify(TwoFactorVerifyRequest $request)
    {
        Auth::loginUsingId($request->user()->id, session('twofactor')->remember);

        session()->forget('twofactor');

        return redirect()->intended($this->redirectPath());
    }

    /**
     * Where to redirect user on successful verification.
     *
     * @return string
     */
    protected function redirectPath()
    {
        return $this->redirectTo;
    }
}
