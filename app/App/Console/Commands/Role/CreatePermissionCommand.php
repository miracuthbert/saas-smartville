<?php

namespace Smartville\App\Console\Commands\Role;

use Illuminate\Console\Command;
use Smartville\Domain\Users\Models\Permission;
use Smartville\Domain\Users\Models\Role;

class CreatePermissionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:create {name} {--usable} {--for_company} {--role=null}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new user permission with an option of to assign a role to it';

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @var Role
     */
    private $role;

    /**
     * Create a new command instance.
     *
     * @param Permission $permission
     * @param Role $role
     */
    public function __construct(Permission $permission, Role $role)
    {
        parent::__construct();

        $this->permission = $permission;
        $this->role = $role;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // permission name
        $name = str_replace('-', ' ', $this->argument('name'));

        // permission usable
        $usable = $this->option('usable');

        // permission for company
        $for_company = $this->option('for_company');

        // role slug
        $slug = $this->option('role', null);

        try {
            // find role or set null
            $role = $slug != 'null' ? $this->role->where('slug', $slug)->firstOrFail() : null;

            if ($this->confirm(sprintf("Are you sure you want to create '%s' permission?", $name))) {

                // create permission
                $permission = $this->permission->fill([
                    'name' => $name,
                    'usable' => $usable,
                    'for_company' => $for_company,
                ]);
                $permission->save();

                // print success
                $this->info(sprintf('Created permission: `%s`', $name));

                if ($role != null && !$role->permissions->contains($permission)) {

                    $role->permissions()->attach($permission);

                    // print success
                    $this->info(sprintf('Assigned permission: `%s` to `%s` role', $name, $role->name));
                }
            }
        } catch (\Exception $exception) {
            $this->error(
                sprintf(
                    'Whoops! we could not create permission because: %s',
                    $exception->getMessage()
                )
            );
        }
    }
}
