<?php

namespace Smartville\Domain\Company\Listeners;

use Smartville\Domain\Company\Events\CompanyCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
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

        //todo: get ids by scoping permissions according to role
        $permissions = Permission::usable()->forCompany()->pluck('id')->all();

        foreach ($this->roles as $role) {
            $role = $tenant->roles()->create([
                'name' => ucfirst($role),
                'usable' => 1
            ]);

            // todo: dispatch job to create roles

            if ($role->name == ucfirst('administrator')) {
                $role->addPermissions($permissions);
            }
        }
    }
}
