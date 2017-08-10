<?php

$factory->define('App\Meal', function (Faker\Generator $faker) {
    return [
        'slug' => $faker->word,
        'status' => $faker->numberBetween(1, 3),
//        'category_id' => $faker->numberBetween(1, 3), TODO add category id
        'created_at' => $faker->dateTime(),
        'updated_at' => $faker->dateTime(),
    ];
});