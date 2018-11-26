<?php

use Illuminate\Database\Seeder;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = \Smartville\Domain\Company\Models\Company::limit(2)->get();

        $companies->each(function ($u) {
            $u->projects()->saveMany(factory(\Smartville\Domain\Projects\Models\Project::class, 5)->make());
        });

    }
}
