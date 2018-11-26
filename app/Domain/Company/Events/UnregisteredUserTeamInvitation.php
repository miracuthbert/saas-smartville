<?php

namespace Smartville\Domain\Company\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Smartville\Domain\Company\Models\Company;
use Smartville\Domain\Users\Models\UserInvitation;

class UnregisteredUserTeamInvitation
{
    use Dispatchable, SerializesModels;

    /**
     * Invitation Company.
     *
     * @var Company
     */
    public $company;

    /**
     * User invitation.
     *
     * @var UserInvitation
     */
    public $invitation;

    /**
     * Create a new event instance.
     *
     * @param Company $company
     * @param UserInvitation $invitation
     */
    public function __construct(Company $company, UserInvitation $invitation)
    {
        $this->company = $company;
        $this->invitation = $invitation;
    }
}
