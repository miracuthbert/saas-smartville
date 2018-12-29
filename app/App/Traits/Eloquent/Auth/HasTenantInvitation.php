<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 8/3/2018
 * Time: 12:01 AM
 */

namespace Smartville\App\Traits\Eloquent\Auth;

use Carbon\Carbon;
use Smartville\Domain\Leases\Models\Lease;

trait HasTenantInvitation
{
    public function confirmTenantInvitation($propertyId, $invitationId)
    {
        // fetch lease
        $lease = $this->fetchLease($propertyId, $invitationId);

        // return false if lease does not exist
        if (!$lease && !$lease->exists) {
            return false;
        }

        // activate user
        $this->activateUserViaTenantInvitation();

        // return false if invitation does not exist
        if (!$lease->invitation && !$lease->invitation->exists) {
            return false;
        }

        // accept invitation
        $this->acceptTenantInvitation($lease);

        // set property status as occupied
        $lease->property->updatePropertyOccupancyStatus();

        // add tenant to company users
        $this->addTenantAsUserToCompany($lease->property->company);

        // update user lease
        $this->updateLease($lease);

        return true;
    }

    public function resumeTenantInvitation($propertyId, $invitationId)
    {
        // fetch lease
        $lease = $this->fetchLease($propertyId, $invitationId);

        // return false if lease does not exist
        if (!$lease && !$lease->exists) {
            return false;
        }

        // accept invitation
        $this->acceptTenantInvitation($lease);

        // set property status as occupied
        $lease->property->updatePropertyOccupancyStatus();

        // add tenant to company users
        $this->addTenantAsUserToCompany($lease->property->company);

        // update user lease
        $this->updateLease($lease);

        return true;
    }

    protected function fetchLease($propertyId, $invitationId)
    {
        $lease = Lease::with('property', 'invitation')->where('property_id', $propertyId)
            ->where('invitation_id', $invitationId)->first();

        return $lease;
    }

    protected function updateLease($lease)
    {
        $lease->user()->associate($this);
        $lease->save();
    }

    protected function acceptTenantInvitation($lease)
    {
        $lease->invitation()->update([
            'accepted_at' => Carbon::now()
        ]);
    }

    protected function activateUserViaTenantInvitation()
    {
        $this->update([
            'activated' => true
        ]);
    }

    /**
     * Add tenant to company users.
     *
     * @param $company
     */
    protected function addTenantAsUserToCompany($company)
    {
        $this->companies()->syncWithoutDetaching($company->id);
    }
}