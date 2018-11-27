<?php

namespace Smartville\Domain\Company\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Smartville\App\Tenant\Models\Tenant;
use Smartville\Domain\Company\Models\CompanyRole;
use Smartville\Domain\Users\Models\Permission;
use Smartville\Domain\Users\Models\User;

class CreateDefaultCompanyRoles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Tenant
     */
    protected $tenant;

    /**
     * @var array
     */
    protected $roles;

    /**
     * @var User
     */
    protected $user;

    /**
     * Create a new job instance.
     *
     * @param $tenant
     * @param $roles
     * @param $user
     */
    public function __construct(Tenant $tenant, array $roles, User $user)
    {
        $this->tenant = $tenant;
        $this->roles = CompanyRole::$defaultRoles;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //todo: get ids by scoping permissions according to role
        $permissions = Permission::usable()->forCompany()->get();

        foreach ($this->roles as $role) {
            $role = $this->tenant->roles()->create([
                'name' => ucfirst($role),
                'slug' => str_slug(($this->tenant->short_name . ' ' . $role)),
                'usable' => 1
            ]);

            if ($role->name == ucfirst('administrator')) {
                $role->addPermissions($permissions->pluck('id'));
            } else {
                $role->addPermissions($permissions->where('name', 'browse company admin')->pluck('id'));
            }
        }

        // dispatch job to create admin
        dispatch(new CreateDefaultCompanyAdmin($this->tenant, $this->user));
    }
}
