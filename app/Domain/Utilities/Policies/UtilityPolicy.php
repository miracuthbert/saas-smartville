<?php

namespace Smartville\Domain\Utilities\Policies;

use Smartville\Domain\Users\Models\User;
use Smartville\Domain\Utilities\Models\Utility;
use Illuminate\Auth\Access\HandlesAuthorization;

class UtilityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the utility.
     *
     * @param  \Smartville\Domain\Users\Models\User  $user
     * @param  \Smartville\Domain\Utilities\Models\Utility  $utility
     * @return mixed
     */
    public function view(User $user, Utility $utility)
    {
        return $user->can('view utility');
    }

    /**
     * Determine whether the user can browse through utilities.
     *
     * @param  \Smartville\Domain\Users\Models\User  $user
     * @return mixed
     */
    public function browse(User $user)
    {
        return $user->can('browse utilities');
    }

    /**
     * Determine whether the user can create utilities.
     *
     * @param  \Smartville\Domain\Users\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create utility');
    }

    /**
     * Determine whether the user can update the utility.
     *
     * @param  \Smartville\Domain\Users\Models\User  $user
     * @param  \Smartville\Domain\Utilities\Models\Utility  $utility
     * @return mixed
     */
    public function update(User $user, Utility $utility)
    {
        return $user->can('update utility');
    }

    /**
     * Determine whether the user can delete the utility.
     *
     * @param  \Smartville\Domain\Users\Models\User  $user
     * @param  \Smartville\Domain\Utilities\Models\Utility  $utility
     * @return mixed
     */
    public function delete(User $user, Utility $utility)
    {
        return $user->can('delete utility');
    }
}
