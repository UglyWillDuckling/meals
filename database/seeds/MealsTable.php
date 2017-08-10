<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class MealsTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        factory(App\Meal::class, 5)->create()->each(function ($meal) use ($faker) {
//            $meal->tags()->save(factory(App\Tag::class)->make());
//            $meal->ingredients()->save(factory(App\Ingredient::class)->make());

            DB::table('meals_translation')->insert([
                'meal_id' => $meal->id,
                'language_id' => 1,
                'title' => $meal->slug,
                'description' => $faker->paragraph(1),
                'created_at' => $faker->dateTime(),
                'updated_at' => $faker->dateTime(),
            ]);

        });
    }
}
