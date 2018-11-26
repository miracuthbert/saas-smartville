<?php

namespace Smartville\Domain\Properties\Listeners\Tenant\Invitations;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Smartville\Domain\Properties\Mail\Invitations\UnregisteredUserTenantInvitationEmail;

class SendUnregisteredUserTenantInvitationEmail implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     */
    public function handle($event)
    {
        $property = $event->property;
        $invitation = $event->invitation;

        // send tenant invitation email
        Mail::to($invitation->email, $invitation->name)->send(new UnregisteredUserTenantInvitationEmail($property, $invitation));
    }
}
