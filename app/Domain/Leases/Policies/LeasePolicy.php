<?php

namespace Smartville\Domain\Leases\Policies;

use Smartville\Domain\Users\Models\User;
use Smartville\Domain\Leases\Models\Lease;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the lease.
     *
     * @param  \Smartville\Domain\Users\Models\User  $user
     * @param  \Smartville\Domain\Leases\Models\Lease  $lease
     * @return mixed
     */
    public function view(User $user, Lease $lease)
    {
        if ($this->touch($user, $lease)) {
            return true;
        }

        return $user->can('view lease');
    }

    /**
     * Determine whether the user can browse through leases.
     *
     * @param  \Smartville\Domain\Users\Models\User  $user
     * @return mixed
     */
    public function browse(User $user)
    {
        return $user->can('browse leases');
    }

    /**
     * Determine whether the user can create leases.
     *
     * @param  \Smartville\Domain\Users\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create lease');
    }

    /**
     * Determine whether the user can update the lease.
     *
     * @param  \Smartville\Domain\Users\Models\User  $user
     * @param  \Smartville\Domain\Leases\Models\Lease  $lease
     * @return mixed
     */
    public function update(User $user, Lease $lease)
    {
        if ($this->touch($user, $lease)) {
            return true;
        }

        return $user->can('update lease');
    }

    /**
     * Determine whether the user can delete the lease.
     *
     * @param  \Smartville\Domain\Users\Models\User  $user
     * @param  \Smartville\Domain\Leases\Models\Lease  $lease
     * @return mixed
     */
    public function delete(User $user, Lease $lease)
    {
        if ($this->touch($user, $lease)) {
            return true;
        }

        return $user->can('delete lease');
    }

    /**
     * Determine whether the user can perform any action on the lease.
     *
     * @param User $user
     * @param Lease $lease
     * @return bool
     */
    public function touch(User $user, Lease $lease)
    {
        return $user->id == $lease->user_id;
    }
}
