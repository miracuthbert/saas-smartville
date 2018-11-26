<?php

namespace Smartville\Domain\Tenant\Listeners;

use Carbon\Carbon;
use Smartville\Domain\Company\Models\CompanyRole;
use Smartville\Domain\Tenant\Events\TenantIdentified;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Smartville\Domain\Users\Models\Permission;

class SetupCompanyRoles
{
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
     * @param  TenantIdentified $event
     * @return void
     */
    public function handle(TenantIdentified $event)
    {
        $tenant = $event->tenant;

        $permissions = Permission::usable()->forCompany()->pluck('id');

        // load tenant users and permissions
        $tenant->load(['roles', 'users.companyRoles']);

        // check or create administrator role
        $role = ($tenant->roles()->firstOrCreate(
            ['name' => ucfirst('administrator')], ['usable' => 1]
        ));

        // load role active users
        $role->load([
            'users' => function ($query) {
                return $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', Carbon::now())
                    ->orderBy('expires_at', 'asc');
            },
            'permissions'
        ]);

        // check if role has all company permissions
        if ($role->permissions->count() != $permissions->count()) {
            $role->syncPermissions($permissions->all());
        }

        // check if any company user is Admin or assign to first user
        if (!($role->users->count())) {
            $tenant->users()->first()->companyRoles()->syncWithoutDetaching($role->id);
        }
    }
}
