<?php

namespace Smartville\Domain\Users\Events;

use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateUserLastLogin
{
    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     */
    public function handle($event)
    {
        $user = $event->user;

        $user->last_login_at = Carbon::now();
        $user->save();
    }
}
