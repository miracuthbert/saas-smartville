<?php

namespace Smartville\Domain\Users\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Smartville\Domain\Users\Mail\UserInvitationEmail;

class SendUserInvitationEmail
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $invitation = $event->invitation;

        Mail::to($invitation->email, $invitation->name)->send(new UserInvitationEmail($invitation));
    }
}
