<?php

namespace Smartville\Domain\Users\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Smartville\Domain\Users\Models\UserInvitation;

class UserInvitationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Instance of user's invitation.
     *
     * @var UserInvitation
     */
    public $invitation;

    /**
     * Email heading.
     *
     * @var $heading
     */
    public $heading;

    /**
     * Create a new message instance.
     *
     * @param UserInvitation $invitation
     */
    public function __construct(UserInvitation $invitation)
    {
        $this->invitation = $invitation;
        $this->subject = $this->getInvitationSubject();
        $this->heading = $this->getEmailHeading();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.user.invitations.new');
    }

    /**
     * Set email subject based on invitation type.
     *
     * @return string
     */
    private function getInvitationSubject()
    {
        if ($this->invitation->type == 'user_invitation') {
            return 'User Invitation';
        } else {
            return 'Admin Invitation';
        }
    }

    /**
     * Set email heading based on invitation type.
     *
     * @return string
     */
    private function getEmailHeading()
    {
        if ($this->invitation->type == 'user_invitation') {
            return 'User Invitation';
        } else {
            return 'Admin Invitation';
        }
    }
}
