<?php

namespace Smartville\Domain\Users\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Smartville\Domain\Users\Models\UserInvitation;

class NewUserInvited
{
    use Dispatchable, SerializesModels;

    /**
     * Instance of user's invitation.
     *
     * @var UserInvitation
     */
    public $invitation;

    /**
     * Create a new event instance.
     *
     * @param UserInvitation $invitation
     */
    public function __construct(UserInvitation $invitation)
    {
        $this->invitation = $invitation;
    }
}
