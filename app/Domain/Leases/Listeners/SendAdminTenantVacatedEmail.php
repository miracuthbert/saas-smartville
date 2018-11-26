<?php

namespace Smartville\Domain\Leases\Listeners;

use Smartville\Domain\Leases\Events\TenantVacated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAdminTenantVacatedEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TenantVacated  $event
     * @return void
     */
    public function handle(TenantVacated $event)
    {
        //
    }
}
