<?php

namespace Smartville\Http\Tenant\Controllers\Account;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Company\Events\ExistingUserTeamInvitation;
use Smartville\Domain\Company\Events\UnregisteredUserTeamInvitation;
use Smartville\Domain\Company\Models\CompanyRole;
use Smartville\Domain\Users\Models\User;
use Smartville\Http\Tenant\Requests\TeamMemberStoreRequest;

class TeamMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $members = $request->tenant()->users()->with([
            'companyRoles' => function ($query) {
                return $query->whereNull('expires_at')->orWhere('expires_at', '>', Carbon::now());
            }
        ])->whereHas('companyRoles', function ($query) {
            return $query->whereNull('expires_at')->orWhere('expires_at', '>', Carbon::now());
        })->paginate();

        return view('tenant.account.team.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('create company users')) {
            return redirect()->route('tenant.dashboard');
        }

        return view('tenant.account.team.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TeamMemberStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeamMemberStoreRequest $request)
    {
        if (Gate::denies('create company users')) {
            return redirect()->route('tenant.dashboard');
        }

        $email = $request->email;
        $name = $request->name;
        $data = [
            'role_id' => $request->role
        ];

        $company = $request->tenant();

        $user = User::where('email', $email)->first();

        if ($user && $user->exists) {
            $user->companies()->syncWithoutDetaching($company->id);

            $user->companyRoles()->syncWithoutDetaching($request->role);

            // call event to send invitation
            event(new ExistingUserTeamInvitation($company, $user));

            return redirect()->route('tenant.account.team.index')
                ->withSuccess("{$name} added to team.");
        }

        // create invitation
        $invitation = $company->generateInvitationToken($email, $name, "company_team_invitation", $data);

        // call event to send invitation
        event(new UnregisteredUserTeamInvitation($company, $invitation));

        return redirect()->route('tenant.account.team.index')
            ->withSuccess("{$name} added to team. Their details will be updated once they sign up.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, User $user)
    {
        $company_user = $user->companies()->where('company_id', $request->tenant()->id)->first();

        return view('tenant.account.team.edit', compact('user', 'company_user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        if (Gate::denies('delete company users')) {
            return redirect()->route('tenant.dashboard');
        }

        // get company
        $company = $request->tenant();

        // check if company has at least more than one user
        if ($team = $company->users->count() == 1) {
            return back()->withError('Company must have at least one user.');
        }

        // check if user is trying to remove themselves
        if ($user->isTheSameAs($request->user())) {
            return back()->withWarning('You cannot remove yourself from the team.');
        }

        // get admin role with active users
        $role = $company->roles()->with([
            'users' => function($query) {
                return $query->whereNull('expires_at')->orWhere('expires_at', '>', Carbon::now());
            }
        ])->where('name', ucfirst(array_first(CompanyRole::$defaultRoles)))->first();

        // check if user being removed is the only admin
        if ($role->users->count() == 1 && $role->users->contains('id', $user->id)) {
            return back()->withWarning('You cannot remove the only administrator.');
        }

        // check if the authorized user is an admin
        if (!$role->users->contains('id', auth()->user()->id)) {
            return back()->withWarning('Only admins can remove another administrator.');
        }

        try {
            $expiresAt = Carbon::now();

            foreach ($user->companyRoles as $companyRole) {
                $user->companyRoles()->updateExistingPivot($companyRole->id, ['expires_at' => $expiresAt]);
            }

            $company->users()->detach($user);
        } catch (\Exception $e) {
            return back()->withError("Failed removing {$user->name} from team. Please, try again!");
        }

        // todo: call event to send membership removal

        return back()->withSuccess("{$user->name} removed from team.");
    }
}
