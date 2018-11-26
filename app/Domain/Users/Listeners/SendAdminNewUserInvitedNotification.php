<?php

namespace Smartville\Domain\Users\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendAdminNewUserInvitedNotification
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
        Log::info("Hi Admins, a new invitation has been sent out to {$invitation->name}. Hooray!");
    }
}
