<?php

namespace Smartville\Http\Tenant\Controllers\Role;

use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Smartville\Domain\Company\Models\CompanyRole;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Users\Models\Permission;
use Smartville\Http\Tenant\Requests\RoleStoreRequest;
use Smartville\Http\Tenant\Requests\RoleUpdateRequest;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Gate::denies('browse company roles')) {
            return redirect()->route('tenant.dashboard');
        }

        $roles = CompanyRole::with([
            'permissions',
            'users' => function ($query) {
                return $query->whereNull('expires_at')
                    ->orWhereDate('expires_at', '>', Carbon::now());
            },
        ])->filter($request)->paginate();

        return view('tenant.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('create company roles')) {
            return redirect()->route('tenant.dashboard');
        }

        return view('tenant.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RoleStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleStoreRequest $request)
    {
        if (Gate::denies('create company roles')) {
            return redirect()->route('tenant.dashboard');
        }

        $role = new CompanyRole();
        $role->fill($request->only('name', 'details'));
        $role->save();

        $role->addPermissions($request->permissions);

        return redirect()->route('tenant.roles.index')
            ->withSuccess("{$role->name} role created successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Company\Models\CompanyRole $companyRole
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyRole $companyRole)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Smartville\Domain\Company\Models\CompanyRole $companyRole
     * @return \Illuminate\Http\Response
     */
    public function edit(CompanyRole $companyRole)
    {
        if (Gate::denies('update company roles')) {
            return redirect()->route('tenant.dashboard');
        }

        return view('tenant.roles.edit', compact('companyRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RoleUpdateRequest $request
     * @param  \Smartville\Domain\Company\Models\CompanyRole $companyRole
     * @return \Illuminate\Http\Response
     */
    public function update(RoleUpdateRequest $request, CompanyRole $companyRole)
    {
        if (Gate::denies('update company roles')) {
            return redirect()->route('tenant.dashboard');
        }

        $data = $request->only('name', 'details', 'usable');

        foreach (CompanyRole::$defaultRoles as $role) {
            if ($role === strtolower($companyRole->name)) {
                $data = $request->only('details');
            }
        }

        $companyRole->fill($data);
        $companyRole->save();

        $companyRole->syncPermissions($request->permissions);

        return back()->withSuccess("{$companyRole->name} role updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Company\Models\CompanyRole $companyRole
     * @return void
     * @throws \Exception
     */
    public function destroy(CompanyRole $companyRole)
    {
        if (Gate::denies('delete company roles')) {
            return redirect()->route('tenant.dashboard');
        }

        foreach (CompanyRole::$defaultRoles as $role) {
            if ($role === strtolower($companyRole->name)) {
                return back()->withError("You cannot delete default roles. Failed deleting {$companyRole->name} role.");
            }
        }

        if ($companyRole->users->count() > 0) {
            return back()->withWarning("Failed deleting {$companyRole->name} role.");
        }

        try {
            $companyRole->delete();
        } catch (\Exception $e) {
            return back()->withError("Failed deleting {$companyRole->name} role.");
        }

        return back()->withSuccess("Role `{$companyRole->name}` deleted successfully.");
    }
}
