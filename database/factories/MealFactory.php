<?php

$factory->define('App\Meal', function (Faker\Generator $faker) {
    $categories = DB::table('category')->get()->toArray();

    return [
        'slug' => $faker->word,
        'status' => $faker->numberBetween(1, 3),
        'category_id' => $faker->numberBetween(1, sizeof($categories)),
        'created_at' => $faker->dateTime(),
        'updated_at' => $faker->dateTime(),
    ];
});