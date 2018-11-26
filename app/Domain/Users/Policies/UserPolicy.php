<?php

namespace Smartville\Domain\Users\Policies;

use Smartville\Domain\Users\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @return mixed
     */
    public function view(User $user)
    {
        if ($this->touch($user) || $user->can('view user')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @return mixed
     */
    public function browse(User $user)
    {
        if ($this->touch($user) || $user->can('browse users')) {
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->can('create user')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @return mixed
     */
    public function update(User $user)
    {
        if ($user->can('update user')) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @return mixed
     */
    public function delete(User $user)
    {
        if ($user->can('delete user')) {
            return true;
        }
    }

    /**
     * Determine whether the user can perform any action on the model.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @return mixed
     */
    public function touch(User $user)
    {
        if ($user->can('assign roles')) {
            return true;
        }
    }
}
