<?php

namespace Smartville\Domain\Company\Mail\Invitations;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Smartville\Domain\Company\Models\Company;
use Smartville\Domain\Users\Models\UserInvitation;

class UnregisteredUserTeamInvitationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Invitation company.
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
     * Create a new message instance.
     *
     * @param Company $company
     * @param UserInvitation $invitation
     */
    public function __construct(Company $company, UserInvitation $invitation)
    {
        $this->company = $company;
        $this->invitation = $invitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Team Invitation")
            ->markdown('tenant.emails.company.team.invitations.user.unregistered.invite');
    }
}
