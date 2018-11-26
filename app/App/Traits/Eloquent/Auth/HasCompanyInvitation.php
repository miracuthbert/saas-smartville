<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 8/19/2018
 * Time: 12:32 AM
 */

namespace Smartville\App\Traits\Eloquent\Auth;

use Carbon\Carbon;
use Smartville\Domain\Company\Models\Company;
use Smartville\Domain\Users\Models\UserInvitation;

trait HasCompanyInvitation
{
    /**
     * Handles user's company invitation.
     *
     * @param $companyId
     * @param $invitationId
     * @return bool
     */
    public function confirmCompanyInvitation($companyId, $invitationId)
    {
        // fetch company
        $company = Company::find($companyId);

        if (!$company && !$company->exists) {
            return false;
        }

        // fetch invitation
        $invitation = UserInvitation::find($invitationId);

        if (!$invitation && !$invitation->exists) {
            return false;
        }

        // accept invitation
        $this->acceptCompanyInvitation($invitation);

        // activate user
        $this->activateUserViaCompanyInvitation();

        // add user to company
        $this->addUserToCompany($company, $invitation);

        return true;
    }

    /**
     * Retry adding user to company.
     *
     * @param $companyId
     * @param $invitationId
     * @return bool
     */
    public function resumeCompanyInvitation($companyId, $invitationId)
    {
        // fetch company
        $company = Company::find($companyId);

        if (!$company && !$company->exists) {
            return false;
        }

        // fetch invitation
        $invitation = UserInvitation::find($invitationId);

        if (!$invitation && !$invitation->exists) {
            return false;
        }

        // accept invitation
        $this->acceptCompanyInvitation($invitation);

        // add user to company
        $this->addUserToCompany($company, $invitation);

        return true;
    }

    /**
     * Accepts user invitation.
     *
     * @param UserInvitation $invitation
     */
    protected function acceptCompanyInvitation(UserInvitation $invitation)
    {
        $invitation->update([
            'accepted_at' => Carbon::now()
        ]);
    }

    /**
     * Activates user via invitation.
     */
    protected function activateUserViaCompanyInvitation()
    {
        $this->update([
            'activated' => true
        ]);
    }

    /**
     * Handles adding user to company.
     *
     * @param $company
     * @param $invitation
     */
    protected function addUserToCompany($company, $invitation)
    {
        // add user to company
        $this->companies()->syncWithoutDetaching($company->id);

        // add user role
        $this->companyRoles()->syncWithoutDetaching([
            $invitation->data['role_id'] => [
                'expires_at' => isset($invitation->data['expires_at']) ? $invitation->data['expires_at'] : null
            ]
        ]);
    }
}