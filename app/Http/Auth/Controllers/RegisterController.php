<?php

namespace Smartville\Http\Auth\Controllers;

use Smartville\App\Controllers\Controller;
use Smartville\Domain\Auth\Events\UserSignedUp;
use Smartville\Domain\Leases\Models\Lease;
use Smartville\Domain\Properties\Models\Property;
use Smartville\Domain\Users\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Smartville\Domain\Users\Models\UserInvitation;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|string|max:30',
            'last_name' => 'required|string|max:30',
            'username' => 'nullable|string|max:30|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'terms' => 'required|accepted'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \Smartville\Domain\Users\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'activated' => false,
        ]);
    }

    /**
     * The user has been registered.
     *
     * @param Request $request
     * @param  mixed $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        // check if user signed up via tenant invitation
        if (session()->has('tenant_invitation')) {
            return $this->completeTenantInvitation($user);
        }

        // check if user signed up via company invitation
        if (session()->has('company_invitation')) {
            return $this->completeCompanyInvitation($user);
        }

        //log user out
        $this->guard()->logout();

        //send user an activation email
        event(new UserSignedUp($user));

        //redirect user
        return redirect($this->redirectPath())
            ->withSuccess('Thank you for signing up. Please check your email for an activation link to proceed.');
    }

    /**
     * Complete user registration via company team invitation.
     *
     * @param $user
     * @return mixed
     */
    protected function completeCompanyInvitation($user)
    {
        $companyId = session('company_invitation.company');
        $invitationId = session('company_invitation.invitation');

        $confirm = $user->confirmCompanyInvitation($companyId, $invitationId);

        if (!$confirm) {
            $invitation = UserInvitation::find($invitationId);

            return redirect()->route('account.dashboard')
                ->withWarning('Thank you for signing up. Some error occurred while setting up for company access.')
                ->with('warning_link', route('account.companies.setup.resume', [$companyId, $invitation]))
                ->with('alert_link_name', 'Resume company setup');
        }

        // remove tenant invitation from session
        session()->forget('company_invitation');

        // redirect to tenant dashboard
        return redirect()->route('tenant.switch', $companyId)
            ->withSuccess('Thank you for signing up. Your account has been activated.')
            ->withInfo("You can now access company's dashboard.");
    }

    /**
     * Complete user registration via tenant invitation.
     *
     * @param $user
     * @return mixed
     */
    protected function completeTenantInvitation($user)
    {
        $propertyId = session('tenant_invitation.property');
        $invitationId = session('tenant_invitation.invitation');

        $confirm = $user->confirmTenantInvitation($propertyId, $invitationId);

        if (!$confirm) {
            $property = Property::find($propertyId);
            $invitation = UserInvitation::find($invitationId);

            return redirect()->route('account.dashboard')
                ->withWarning('Thank you for signing up. Some error occurred while trying to setup your lease.')
                ->with('warning_link', route('account.leases.setup.resume', [$property, $invitation]))
                ->with('alert_link_name', 'Resume lease setup');
        }

        // find lease
        $lease = Lease::where('property_id', $propertyId)->where('invitation_id', $invitationId)->first();

        // remove tenant invitation from session
        session()->forget('tenant_invitation');

        // redirect to tenant dashboard
        return redirect()->route('account.leases.show', $lease)
            ->withSuccess('Thank you for signing up. Your account has been activated.')
            ->withInfo('Your lease has been setup successfully.');
    }
}
