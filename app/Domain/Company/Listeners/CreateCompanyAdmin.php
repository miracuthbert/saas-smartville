<?php

namespace Smartville\Domain\Company\Listeners;

use Illuminate\Support\Facades\Mail;
use Smartville\Domain\Company\Events\CompanyCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Smartville\Domain\Company\Mail\AdminWelcomeEmail;

class CreateCompanyAdmin
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
        $user = $event->user;

        $role = $company->roles()->where('name', 'Administrator')->first();

        $user->companyRoles()->syncWithoutDetaching($role->id);

        // send mail to user
        Mail::to($user)->send(new AdminWelcomeEmail($company, $user));
    }
}
