<?php

use Illuminate\Database\Seeder;
use Smartville\Domain\Users\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $permissions = [
            [
                'name' => 'view company account',
                'for_company' => true,
            ],
            [
                'name' => 'update company account',
                'for_company' => true,
            ],
            [
                'name' => 'browse company roles',
                'for_company' => true,
            ],
            [
                'name' => 'create company roles',
                'for_company' => true,
            ],
            [
                'name' => 'update company roles',
                'for_company' => true,
            ],
            [
                'name' => 'delete company roles',
                'for_company' => true,
            ],
            [
                'name' => 'assign company roles',
                'for_company' => true,
            ],
            [
                'name' => 'create company users',
                'for_company' => true,
            ],
            [
                'name' => 'update company users',
                'for_company' => true,
            ],
            [
                'name' => 'delete company users',
                'for_company' => true,
            ],
            [
                'name' => 'browse company admin',
                'for_company' => true,
            ],
            [
                'name' => 'view property',
                'for_company' => true,
            ],
            [
                'name' => 'browse properties',
                'for_company' => true,
            ],
            [
                'name' => 'create property',
                'for_company' => true,
            ],
            [
                'name' => 'update property',
                'for_company' => true,
            ],
            [
                'name' => 'delete property',
                'for_company' => true,
            ],
            [
                'name' => 'view lease',
                'for_company' => true,
            ],
            [
                'name' => 'browse leases',
                'for_company' => true,
            ],
            [
                'name' => 'create lease',
                'for_company' => true,
            ],
            [
                'name' => 'update lease',
                'for_company' => true,
            ],
            [
                'name' => 'delete lease',
                'for_company' => true,
            ],
            [
                'name' => 'view amenity',
                'for_company' => true,
            ],
            [
                'name' => 'browse amenities',
                'for_company' => true,
            ],
            [
                'name' => 'create amenity',
                'for_company' => true,
            ],
            [
                'name' => 'update amenity',
                'for_company' => true,
            ],
            [
                'name' => 'delete amenity',
                'for_company' => true,
            ],
            [
                'name' => 'view utility',
                'for_company' => true,
            ],
            [
                'name' => 'browse utilities',
                'for_company' => true,
            ],
            [
                'name' => 'create utility',
                'for_company' => true,
            ],
            [
                'name' => 'update utility',
                'for_company' => true,
            ],
            [
                'name' => 'delete utility',
                'for_company' => true,
            ],
            [
                'name' => 'view lease invoice',
                'for_company' => true,
            ],
            [
                'name' => 'browse lease invoices',
                'for_company' => true,
            ],
            [
                'name' => 'create lease invoice',
                'for_company' => true,
            ],
            [
                'name' => 'update lease invoice',
                'for_company' => true,
            ],
            [
                'name' => 'delete lease invoice',
                'for_company' => true,
            ],
            [
                'name' => 'view lease payment',
                'for_company' => true,
            ],
            [
                'name' => 'browse lease payments',
                'for_company' => true,
            ],
            [
                'name' => 'create lease payment',
                'for_company' => true,
            ],
            [
                'name' => 'update lease payment',
                'for_company' => true,
            ],
            [
                'name' => 'delete lease payment',
                'for_company' => true,
            ],
            [
                'name' => 'view utility invoice',
                'for_company' => true,
            ],
            [
                'name' => 'browse utility invoices',
                'for_company' => true,
            ],
            [
                'name' => 'create utility invoice',
                'for_company' => true,
            ],
            [
                'name' => 'update utility invoice',
                'for_company' => true,
            ],
            [
                'name' => 'delete utility invoice',
                'for_company' => true,
            ],
            [
                'name' => 'view utility payment',
                'for_company' => true,
            ],
            [
                'name' => 'browse utility payments',
                'for_company' => true,
            ],
            [
                'name' => 'create utility payment',
                'for_company' => true,
            ],
            [
                'name' => 'update utility payment',
                'for_company' => true,
            ],
            [
                'name' => 'delete utility payment',
                'for_company' => true,
            ],
        ];

        $permissions = collect($permissions)->whereNotIn('name', Permission::pluck('name')->all());

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
