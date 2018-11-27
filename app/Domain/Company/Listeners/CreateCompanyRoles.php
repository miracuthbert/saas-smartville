<?php

namespace Smartville\Domain\Company\Listeners;

use Smartville\Domain\Company\Events\CompanyCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Smartville\Domain\Company\Jobs\CreateDefaultCompanyRoles;
use Smartville\Domain\Company\Models\CompanyRole;
use Smartville\Domain\Users\Models\Permission;

class CreateCompanyRoles
{
    /**
     * List of default roles.
     *
     * @var array
     */
    public $roles;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->roles = CompanyRole::$defaultRoles;
    }

    /**
     * Handle the event.
     *
     * @param  CompanyCreated $event
     * @return void
     */
    public function handle(CompanyCreated $event)
    {
        $tenant = $event->tenant;
        $user = $event->user;

        // dispatch job to handle creation of roles
        dispatch(new CreateDefaultCompanyRoles($tenant, $this->roles, $user));
    }
}
