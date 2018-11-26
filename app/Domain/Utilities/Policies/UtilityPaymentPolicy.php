<?php

namespace Smartville\Domain\Utilities\Policies;

use Smartville\Domain\Users\Models\User;
use Smartville\Domain\Utilities\Models\UtilityPayment;
use Illuminate\Auth\Access\HandlesAuthorization;

class UtilityPaymentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the utilityPayment.
     *
     * @param  \Smartville\Domain\Users\Models\User  $user
     * @param  \Smartville\Domain\Utilities\Models\UtilityPayment  $utilityPayment
     * @return mixed
     */
    public function view(User $user, UtilityPayment $utilityPayment)
    {
        if ($this->touch($user, $utilityPayment)) {
            return true;
        }

        return $user->can('view utility payment');
    }

    /**
     * Determine whether the user can browse through utilityPayments.
     *
     * @param  \Smartville\Domain\Users\Models\User  $user
     * @return mixed
     */
    public function browse(User $user)
    {
        return $user->can('browse utility payments');
    }

    /**
     * Determine whether the user can create utilityPayments.
     *
     * @param  \Smartville\Domain\Users\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create utility payment');
    }

    /**
     * Determine whether the user can update the utilityPayment.
     *
     * @param  \Smartville\Domain\Users\Models\User  $user
     * @param  \Smartville\Domain\Utilities\Models\UtilityPayment  $utilityPayment
     * @return mixed
     */
    public function update(User $user, UtilityPayment $utilityPayment)
    {
        if ($this->touch($user, $utilityPayment)) {
            return true;
        }

        return $user->can('update utility payment');
    }

    /**
     * Determine whether the user can delete the utilityPayment.
     *
     * @param  \Smartville\Domain\Users\Models\User  $user
     * @param  \Smartville\Domain\Utilities\Models\UtilityPayment  $utilityPayment
     * @return mixed
     */
    public function delete(User $user, UtilityPayment $utilityPayment)
    {
        // optional: check if user is the one who handled payment
        return $user->can('delete utility payment');
    }

    /**
     * Determine whether the user can perform any action on utilityPayment.
     *
     * @param  \Smartville\Domain\Users\Models\User  $user
     * @param  \Smartville\Domain\Utilities\Models\UtilityPayment  $utilityPayment
     * @return mixed
     */
    public function touch(User $user, UtilityPayment $utilityPayment)
    {
        return $user->id == $utilityPayment->invoice->user_id;
    }
}
