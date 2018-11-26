<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Properties',
                'children' => [
                    [
                        'name' => 'Apartment',
                    ],
                    [
                        'name' => 'House',
                    ],
                ]
            ]
        ];

        foreach ($categories as $category) {
            \Smartville\Domain\Categories\Models\Category::create($category);
        }
    }
}
