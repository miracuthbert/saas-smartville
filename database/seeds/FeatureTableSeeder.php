<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Smartville\Domain\Features\Models\Feature;

class FeatureTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('features')->truncate();

        $features = [
            [
                'name' => "Property Manager",
                'overview' => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusamus
                                cumque cupiditate ipsam quidem, quisquam similique.",
                'description' => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusamus
                                cumque cupiditate ipsam quidem, quisquam similique. Alias, commodi et ex laborum maxime
                                minus optio placeat, quam quis sint ullam, veritatis? Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusamus
                                cumque cupiditate ipsam quidem, quisquam similique. Alias, commodi et ex laborum maxime
                                minus optio placeat, quam quis sint ullam, veritatis?",
                'usable' => true,
            ],
            [
                'name' => "Tenant & Lease Manager",
                'overview' => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusamus
                                cumque cupiditate ipsam quidem, quisquam similique.",
                'description' => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusamus
                                cumque cupiditate ipsam quidem, quisquam similique. Alias, commodi et ex laborum maxime
                                minus optio placeat, quam quis sint ullam, veritatis? Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusamus
                                cumque cupiditate ipsam quidem, quisquam similique. Alias, commodi et ex laborum maxime
                                minus optio placeat, quam quis sint ullam, veritatis?",
                'usable' => true,
            ],
            [
                'name' => "Invoice Manager",
                'overview' => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusamus
                                cumque cupiditate ipsam quidem, quisquam similique.",
                'description' => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusamus
                                cumque cupiditate ipsam quidem, quisquam similique. Alias, commodi et ex laborum maxime
                                minus optio placeat, quam quis sint ullam, veritatis? Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusamus
                                cumque cupiditate ipsam quidem, quisquam similique. Alias, commodi et ex laborum maxime
                                minus optio placeat, quam quis sint ullam, veritatis?",
                'usable' => true,
            ],
            [
                'name' => "Email Notifications",
                'overview' => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusamus
                                cumque cupiditate ipsam quidem, quisquam similique.",
                'description' => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusamus
                                cumque cupiditate ipsam quidem, quisquam similique. Alias, commodi et ex laborum maxime
                                minus optio placeat, quam quis sint ullam, veritatis? Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusamus
                                cumque cupiditate ipsam quidem, quisquam similique. Alias, commodi et ex laborum maxime
                                minus optio placeat, quam quis sint ullam, veritatis?",
                'usable' => true,
            ],
            [
                'name' => "Team",
                'overview' => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusamus
                                cumque cupiditate ipsam quidem, quisquam similique.",
                'description' => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusamus
                                cumque cupiditate ipsam quidem, quisquam similique. Alias, commodi et ex laborum maxime
                                minus optio placeat, quam quis sint ullam, veritatis? Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusamus
                                cumque cupiditate ipsam quidem, quisquam similique. Alias, commodi et ex laborum maxime
                                minus optio placeat, quam quis sint ullam, veritatis?",
                'usable' => true,
            ],
            [
                'name' => "Metrics",
                'overview' => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusamus
                                cumque cupiditate ipsam quidem, quisquam similique.",
                'description' => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusamus
                                cumque cupiditate ipsam quidem, quisquam similique. Alias, commodi et ex laborum maxime
                                minus optio placeat, quam quis sint ullam, veritatis? Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusamus
                                cumque cupiditate ipsam quidem, quisquam similique. Alias, commodi et ex laborum maxime
                                minus optio placeat, quam quis sint ullam, veritatis?",
                'usable' => true,
            ],
        ];

        foreach ($features as $feature) {
            Feature::create($feature);
        }
    }
}
