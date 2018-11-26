<?php

namespace Smartville\Domain\Users\Policies;

use Smartville\Domain\Users\Models\User;
use Smartville\Domain\Users\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the role.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @param  \Smartville\Domain\Users\Models\Role $role
     * @return mixed
     */
    public function view(User $user, Role $role)
    {
        return $user->can('view role');
    }

    /**
     * Determine whether the user can browse roles.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @return mixed
     */
    public function browse(User $user)
    {
        return $user->can('browse roles');
    }

    /**
     * Determine whether the user can create roles.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create role');
    }

    /**
     * Determine whether the user can update the role.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @param  \Smartville\Domain\Users\Models\Role $role
     * @return mixed
     */
    public function update(User $user, Role $role)
    {
        return $user->can('update role');
    }

    /**
     * Determine whether the user can delete the role.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @param  \Smartville\Domain\Users\Models\Role $role
     * @return mixed
     */
    public function delete(User $user, Role $role)
    {
        return $user->can('delete role');
    }

    /**
     * Determine whether the user can perform any action on the model.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @return mixed
     */
    public function touch(User $user)
    {
        if ($user->can('browse roles')) {
            return true;
        }
    }
}
