<?php

namespace Smartville\Domain\Leases\Policies;

use Smartville\Domain\Users\Models\User;
use Smartville\Domain\Leases\Models\LeasePayment;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeasePaymentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the leasePayment.
     *
     * @param  \Smartville\Domain\Users\Models\User  $user
     * @param  \Smartville\Domain\Leases\Models\LeasePayment  $leasePayment
     * @return mixed
     */
    public function view(User $user, LeasePayment $leasePayment)
    {
        if ($this->touch($user, $leasePayment)) {
            return true;
        }

        return $user->can('view lease payment');
    }

    /**
     * Determine whether the user can browse through leasePayments.
     *
     * @param  \Smartville\Domain\Users\Models\User  $user
     * @return mixed
     */
    public function browse(User $user)
    {
        return $user->can('browse lease payments');
    }

    /**
     * Determine whether the user can create leasePayments.
     *
     * @param  \Smartville\Domain\Users\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create lease payment');
    }

    /**
     * Determine whether the user can update the leasePayment.
     *
     * @param  \Smartville\Domain\Users\Models\User  $user
     * @param  \Smartville\Domain\Leases\Models\LeasePayment  $leasePayment
     * @return mixed
     */
    public function update(User $user, LeasePayment $leasePayment)
    {
        if ($this->touch($user, $leasePayment)) {
            return true;
        }

        return $user->can('update lease payment');
    }

    /**
     * Determine whether the user can delete the leasePayment.
     *
     * @param  \Smartville\Domain\Users\Models\User  $user
     * @param  \Smartville\Domain\Leases\Models\LeasePayment  $leasePayment
     * @return mixed
     */
    public function delete(User $user, LeasePayment $leasePayment)
    {
        // optional: check if user is the one who handled payment
        return $user->can('delete lease payment');
    }

    /**
     * Determine whether the user can perform any action on leasePayment.
     *
     * @param User $user
     * @param LeasePayment $leasePayment
     * @return bool
     */
    protected function touch(User $user, LeasePayment $leasePayment)
    {
        return $user->id == $leasePayment->lease->user_id;
    }
}
