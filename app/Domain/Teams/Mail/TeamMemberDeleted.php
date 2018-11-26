<?php

namespace Smartville\Domain\Teams\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Smartville\Domain\Teams\Models\Team;
use Smartville\Domain\Users\Models\User;

class TeamMemberDeleted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * User instance.
     *
     * @var User
     */
    public $user;

    /**
     * Team instance.
     *
     * @var Team
     */
    public $team;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param Team $team
     */
    public function __construct(User $user, Team $team)
    {
        $this->user = $user;
        $this->team = $team;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Team Membership Cancelled')->markdown('emails.team.member.deleted');
    }
}
