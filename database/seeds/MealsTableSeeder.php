<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class MealsTableSeeder extends Seeder
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
            $tag = $meal->tags()->save(factory(App\Tag::class)->make());
            $ingredient = $meal->ingredients()->save(factory(App\Ingredient::class)->make());

            foreach (DB::table('language')->get() as $language) {
                DB::table('meals_translation')->insert([
                    'meal_id' => $meal->id,
                    'language_id' => $language->id,
                    'title' => $meal->slug . " {$language->name}",
                    'description' => $faker->paragraph(1) . " {$language->name}",
                    'created_at' => $faker->dateTime(),
                    'updated_at' => $faker->dateTime(),
                ]);

                DB::table('tags_translation')->insert([
                    'tag_id' => $tag->id,
                    'language_id' => $language->id,
                    'title' => $tag->slug . " {$language->name}",
                    'created_at' => $faker->dateTime(),
                    'updated_at' => $faker->dateTime(),
                ]);
                DB::table('ingredients_translation')->insert([
                    'ingredient_id' => $ingredient->id,
                    'language_id' => $language->id,
                    'title' => $ingredient->slug . " {$language->name}",
                    'created_at' => $faker->dateTime(),
                    'updated_at' => $faker->dateTime(),
                ]);

            }
        });
    }
}
