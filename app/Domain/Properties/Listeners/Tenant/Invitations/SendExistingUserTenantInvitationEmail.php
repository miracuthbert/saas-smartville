<?php

namespace Smartville\Domain\Properties\Listeners\Tenant\Invitations;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Smartville\Domain\Properties\Mail\Invitations\ExistingUserTenantInvitationEmail;

class SendExistingUserTenantInvitationEmail
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $property = $event->property;
        $user = $event->user;

        Mail::to($user)->send(new ExistingUserTenantInvitationEmail($property, $user));
    }
}
