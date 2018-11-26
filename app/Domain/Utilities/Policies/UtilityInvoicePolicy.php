<?php

namespace Smartville\Domain\Utilities\Policies;

use Smartville\Domain\Users\Models\User;
use Smartville\Domain\Utilities\Models\UtilityInvoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class UtilityInvoicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the utilityInvoice.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @param  \Smartville\Domain\Utilities\Models\UtilityInvoice $utilityInvoice
     * @return mixed
     */
    public function view(User $user, UtilityInvoice $utilityInvoice)
    {
        if ($this->touch($user, $utilityInvoice)) {
            return true;
        }

        return $user->can('view utility invoice');
    }

    /**
     * Determine whether the user can browse through utilityInvoices.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @return mixed
     */
    public function browse(User $user)
    {
        return $user->can('browse utility invoices');
    }

    /**
     * Determine whether the user can create utilityInvoices.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create utility invoice');
    }

    /**
     * Determine whether the user can update the utilityInvoice.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @param  \Smartville\Domain\Utilities\Models\UtilityInvoice $utilityInvoice
     * @return mixed
     */
    public function update(User $user, UtilityInvoice $utilityInvoice)
    {
        if ($this->touch($user, $utilityInvoice)) {
            return true;
        }

        return $user->can('update utility invoice');
    }

    /**
     * Determine whether the user can delete the utilityInvoice.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @param  \Smartville\Domain\Utilities\Models\UtilityInvoice $utilityInvoice
     * @return mixed
     */
    public function delete(User $user, UtilityInvoice $utilityInvoice)
    {
        return $user->can('delete utility invoice');
    }

    /**
     * Determine whether the user can perform any action on the utilityInvoice.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @param  \Smartville\Domain\Utilities\Models\UtilityInvoice $utilityInvoice
     * @return bool
     */
    public function touch(User $user, UtilityInvoice $utilityInvoice)
    {
        return $user->id == $utilityInvoice->user_id;
    }
}
