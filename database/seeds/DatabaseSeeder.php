<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(PlanTableSeeder::class);
         $this->call(RoleTableSeeder::class);
         $this->call(PermissionTableSeeder::class);
//         $this->call(ProjectTableSeeder::class);
         $this->call(CurrencyTableSeeder::class);
         $this->call(PageTableSeeder::class);
         $this->call(FeatureTableSeeder::class);
         $this->call(CountryTableSeeder::class);
    }
}
