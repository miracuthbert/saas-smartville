<?php

namespace Smartville\Domain\Tenant\Listeners;

use Smartville\App\Tenant\Manager;
use Smartville\Domain\Tenant\Events\TenantIdentified;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterTenant
{
    /**
     * Handle the event.
     *
     * @param  TenantIdentified  $event
     * @return void
     */
    public function handle(TenantIdentified $event)
    {
        app(Manager::class)->setTenant($event->tenant);
    }
}
