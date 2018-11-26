<?php

namespace Smartville\Domain\Company\Listeners;

use Smartville\App\Tenant\Manager;
use Smartville\Domain\Company\Events\CompanyCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterCreatedTenant
{
    /**
     * Handle the event.
     *
     * @param  CompanyCreated  $event
     * @return void
     */
    public function handle(CompanyCreated $event)
    {
        app(Manager::class)->setTenant($event->tenant);
    }
}
