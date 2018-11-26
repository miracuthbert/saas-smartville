<?php

namespace Smartville\Domain\Company\Mail\Invitations;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Smartville\Domain\Company\Models\Company;
use Smartville\Domain\Users\Models\User;

class ExistingUserTeamInvitationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Invitation company.
     *
     * @var Company
     */
    public $company;

    /**
     * The user invited.
     *
     * @var User
     */
    public $user;

    /**
     * Create a new message instance.
     *
     * @param Company $company
     * @param User $user
     */
    public function __construct(Company $company, User $user)
    {
        $this->company = $company;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Team Invitation")
            ->markdown('tenant.emails.company.team.invitations.user.existing.invite');
    }
}
