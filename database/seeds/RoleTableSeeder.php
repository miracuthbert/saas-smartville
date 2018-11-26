<?php

use Illuminate\Database\Seeder;
use Smartville\Domain\Users\Models\Permission;
use Smartville\Domain\Users\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'Admin',
                'children' => [
                    [
                        'name' => 'Root',
                    ],
                ]
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        $permissions = [
            [
                'name' => 'browse admin'
            ],
            [
                'name' => 'view role',
            ],
            [
                'name' => 'browse roles'
            ],
            [
                'name' => 'create role',
            ],
            [
                'name' => 'update role',
            ],
            [
                'name' => 'delete role',
            ],
            [
                'name' => 'assign roles'
            ],
            [
                'name' => 'view user'
            ],
            [
                'name' => 'browse users'
            ],
            [
                'name' => 'create user'
            ],
            [
                'name' => 'update user'
            ],
            [
                'name' => 'delete user'
            ],
            [
                'name' => 'view category'
            ],
            [
                'name' => 'browse categories'
            ],
            [
                'name' => 'create category'
            ],
            [
                'name' => 'update category'
            ],
            [
                'name' => 'delete category'
            ],
            [
                'name' => 'view currency'
            ],
            [
                'name' => 'browse currencies'
            ],
            [
                'name' => 'create currency'
            ],
            [
                'name' => 'update currency'
            ],
            [
                'name' => 'delete currency'
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        $rootPermissions = Permission::notForCompany()->pluck('id');
        $root = Role::where('slug', 'admin-root')->first();

        if ($root != null) {
            $root->addPermissions($rootPermissions);
        }
    }
}
