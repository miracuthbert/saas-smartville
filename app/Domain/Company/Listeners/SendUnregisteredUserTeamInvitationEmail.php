<?php

namespace Smartville\Domain\Company\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Smartville\Domain\Company\Mail\Invitations\UnregisteredUserTeamInvitationEmail;

class SendUnregisteredUserTeamInvitationEmail
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $company = $event->company;
        $invitation = $event->invitation;

        Mail::to($invitation->email, $invitation->name)->send(new UnregisteredUserTeamInvitationEmail($company, $invitation));
    }
}
