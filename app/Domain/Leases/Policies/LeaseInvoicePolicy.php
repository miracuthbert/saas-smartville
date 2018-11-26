<?php

namespace Smartville\Domain\Leases\Policies;

use Smartville\Domain\Users\Models\User;
use Smartville\Domain\Leases\Models\LeaseInvoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeaseInvoicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the leaseInvoice.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @param  \Smartville\Domain\Leases\Models\LeaseInvoice $leaseInvoice
     * @return mixed
     */
    public function view(User $user, LeaseInvoice $leaseInvoice)
    {
        if ($this->touch($user, $leaseInvoice)) {
            return true;
        }

        return $user->can('view lease invoice');
    }

    /**
     * Determine whether the user can browse leaseInvoices.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @return mixed
     */
    public function browse(User $user)
    {
        return $user->can('browse lease invoices');
    }

    /**
     * Determine whether the user can create leaseInvoices.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create lease invoice');
    }

    /**
     * Determine whether the user can update the leaseInvoice.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @param  \Smartville\Domain\Leases\Models\LeaseInvoice $leaseInvoice
     * @return mixed
     */
    public function update(User $user, LeaseInvoice $leaseInvoice)
    {
        if ($this->touch($user, $leaseInvoice)) {
            return true;
        }

        return $user->can('update lease invoice');
    }

    /**
     * Determine whether the user can delete the leaseInvoice.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @param  \Smartville\Domain\Leases\Models\LeaseInvoice $leaseInvoice
     * @return mixed
     */
    public function delete(User $user, LeaseInvoice $leaseInvoice)
    {
        return $user->can('delete lease invoice');
    }

    /**
     * Determine whether the user can perform any action on the leaseInvoice.
     *
     * @param User $user
     * @param LeaseInvoice $leaseInvoice
     * @return bool
     */
    public function touch(User $user, LeaseInvoice $leaseInvoice)
    {
        return $user->id == $leaseInvoice->user_id;
    }
}
