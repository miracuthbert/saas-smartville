<?php

use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Database\Seeder;
use Smartville\Domain\Subscriptions\Models\Plan;

class PlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans = [
            [
                'name' => 'Monthly',
                'slug' => SlugService::createSlug(Plan::class, 'slug', 'Monthly'),
                'gateway_id' => 'month_50',
                'price' => '5000',
                'active' => true,
                'teams_enabled' => false,
                'teams_limit' => null,
            ],
            [
                'name' => 'Monthly for 5 Users',
                'slug' => SlugService::createSlug(Plan::class, 'slug', 'Monthly for 5 Users'),
                'gateway_id' => 'team_month_60',
                'price' => '9900',
                'active' => true,
                'teams_enabled' => true,
                'teams_limit' => 5,
            ],
            [
                'name' => 'Monthly for 10 Users',
                'slug' => SlugService::createSlug(Plan::class, 'slug', 'Monthly for 10 Users'),
                'gateway_id' => 'team_month_100',
                'price' => '19900',
                'active' => true,
                'teams_enabled' => true,
                'teams_limit' => 10,
            ],
        ];


        Plan::insert($plans);
    }
}
