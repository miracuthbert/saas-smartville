<?php

use Faker\Generator as Faker;

$factory->define(Smartville\Domain\Properties\Models\Property::class, function (Faker $faker) {
    return [
        'name' => uniqid(request()->tenant()->short_name),
        'address' => $faker->unique()->address,
        'image' => null,
        'overview_short' => $faker->text(160),
        'overview' => $faker->paragraphs(4, true),
        'currency' => \request()->tenant()->currency,
        'size' => $faker->randomNumber(4),
        'price' => $faker->numberBetween(1000000, 1500000),
        'live' => true,
        'finished' => true,
    ];
});
