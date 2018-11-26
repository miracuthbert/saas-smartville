<?php

namespace Smartville\Http\Admin\Controllers\User;

use Smartville\Domain\Users\Models\Permission;
use Smartville\Domain\Users\Models\Role;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Smartville\Domain\Users\Models\Role $role
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Role $role)
    {
        $this->authorize('update', $role);

        $role->load('permissions');

        return view('admin.users.roles.permissions.index', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Smartville\Domain\Users\Models\Role $role
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request, Role $role)
    {
        $this->authorize('update', $role);

        $role->syncPermissions($request->permissions);

        return back()->withSuccess("Role permissions updated.");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Users\Models\Role $role
     * @param  \Smartville\Domain\Users\Models\Permission $permission
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Role $role, Permission $permission)
    {
        $this->authorize('delete', $role);

        if ($role->permissions->contains($permission)) {

            $role->permissions()->detach($permission->id);

            return back()->withSuccess("{$permission->name} removed from role.");
        }

        return back()->withError("Role does not have {$permission->name} permission.");
    }
}
