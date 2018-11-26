<?php

namespace Smartville\Http\Tenant\Controllers\Role;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Company\Models\CompanyRole;
use Smartville\Domain\Users\Models\User;

class RoleUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param CompanyRole $companyRole
     * @return \Illuminate\Http\Response
     */
    public function index(CompanyRole $companyRole)
    {
        if (Gate::denies('assign company roles')) {
            return redirect()->route('tenant.dashboard');
        }

        $companyRole->load([
            'permissions',
            'users' => function ($query) {
                return $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', Carbon::now())
                    ->orderBy('expires_at', 'asc');
            },
        ]);

        $users = $companyRole->users;

        $members = request()->tenant()->users()
            ->whereNotIn('users.id', $users->pluck('id'))
            ->get();

        return view('tenant.roles.users.index', compact('companyRole', 'users', 'members'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param CompanyRole $companyRole
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, CompanyRole $companyRole)
    {
        if (Gate::denies('assign company roles')) {
            return redirect()->route('tenant.dashboard');
        }

        $expiresAt = $request->expires_at;

        // map users
        $users = collect($request->users)->map(function ($user) use ($expiresAt, $companyRole) {
            return [
                'expires_at' => $expiresAt
            ];
        });

        $companyRole->users()->syncWithoutDetaching($users);

        // count inserts
        $count = $users->count();

        // setup message based on count
        $uCount = $count . ' ' . str_plural('user', $count);

        return back()->withSuccess("{$uCount} assigned role successfully.");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param CompanyRole $companyRole
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompanyRole $companyRole, User $user)
    {
        if (Gate::denies('assign company roles')) {
            return redirect()->route('tenant.dashboard');
        }

        // get company
        $company = $request->tenant();

        // get admin role
        $role = $company->roles()->where('name', ucfirst(array_first(CompanyRole::$defaultRoles)))->first();

        // check if user being removed is the only admin
        if ($companyRole->isTheSameAs($role) && $role->users->count() == 1 && $role->users->contains('id', $user->id)) {
            return back()->withWarning('You cannot remove the only administrator.');
        }

        $expiresAt = Carbon::now();
        $msg = "Revoked role from {$user->name}.";

        if ($request->users[$user->id]['expires_at'] != null) {
            $expiresAt = Carbon::parse($request->users[$user->id]['expires_at']);

            $msg = "Role will be revoked from {$user->name} at {$expiresAt->toDayDateTimeString()}.";
        }

        $user->companyRoles()->updateExistingPivot($companyRole->id, ['expires_at' => $expiresAt]);

        return back()->withSuccess($msg);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param CompanyRole $companyRole
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, CompanyRole $companyRole)
    {
        if (Gate::denies('assign company roles')) {
            return redirect()->route('tenant.dashboard');
        }

        if (array_first(CompanyRole::$defaultRoles) === strtolower($companyRole->name)) {
            return back()->withError('You cannot revoke all users from a primary role.');
        }

        $expiresAt = Carbon::now();

        // map users
        $users = $request->users != null ? $request->users : $companyRole->users->mapWithKeys(function ($user) {
            return [
                $user->id => $user->id
            ];
        });

        $users = collect($users)->map(function ($user) use ($expiresAt, $companyRole) {
            return [
                'expires_at' => $expiresAt
            ];
        });

        // set expire
        $companyRole->users()->syncWithoutDetaching($users);

        // count updates
        $count = $users->count();

        // setup users count based on updates
        $uCount = $count . ' ' . str_plural('user', $count);

        return back()->withSuccess("Role revoked from {$uCount}.");
    }
}
