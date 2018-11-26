<?php

namespace Smartville\Domain\Company\Listeners;

use Illuminate\Support\Facades\Mail;
use Smartville\Domain\Company\Events\CompanyCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Smartville\Domain\Company\Mail\CompanyWelcomeEmail;

class SendCompanyWelcomeEmail
{
    /**
     * Handle the event.
     *
     * @param  CompanyCreated  $event
     * @return void
     */
    public function handle(CompanyCreated $event)
    {
        Mail::to($event->tenant)->send(new CompanyWelcomeEmail($event->tenant));
    }
}
