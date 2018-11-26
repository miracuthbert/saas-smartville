<?php

use Faker\Generator as Faker;

$factory->define(Smartville\Domain\Pages\Models\Page::class, function (Faker $faker) {
    return [
        'title' => $faker->unique()->words(2, true),
        'name' => $faker->unique()->words(1, true),
        'body' => $faker->unique()->paragraphs(6, true),
        'usable' => true,
    ];
});
