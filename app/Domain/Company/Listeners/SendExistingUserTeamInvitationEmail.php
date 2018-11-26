<?php

namespace Smartville\Domain\Company\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Smartville\Domain\Company\Mail\Invitations\ExistingUserTeamInvitationEmail;

class SendExistingUserTeamInvitationEmail
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
        $user = $event->user;

        Mail::to($user)->send(new ExistingUserTeamInvitationEmail($company, $user));
    }
}
