<?php

namespace Smartville\Domain\Properties\Listeners\Tenant\Leases;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Smartville\Domain\Leases\Models\Lease;

class CreateExistingUserTenantLease
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

        $lease = new Lease();
        $lease->fill([
            'moved_in_at' => $event->movesIn,
            'moved_out_at' => $event->movesOut,
        ]);
        $lease->property()->associate($property);
        $lease->user()->associate($user);
        $lease->save();
    }
}
