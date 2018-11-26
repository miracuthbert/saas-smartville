<?php

namespace Smartville\Domain\Company\Listeners;

use Illuminate\Support\Facades\Artisan;
use PropertyTableSeeder;
use Smartville\Domain\Company\Events\CompanyCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SeedCompany
{
    /**
     * Handle the event.
     *
     * @param  CompanyCreated  $event
     * @return void
     */
    public function handle(CompanyCreated $event)
    {
        $company = $event->tenant;

        Artisan::call('company:seed', [
            'company' => $company->id
        ]);
    }
}
