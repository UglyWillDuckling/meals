<?php

$factory->define('App\Tag', function (Faker\Generator $faker) {
    return [
        'slug' => $faker->word,
        'created_at' => $faker->dateTime(),
        'updated_at' => $faker->dateTime(),
    ];
});