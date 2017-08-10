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
        $faker = Faker\Factory::create();

        foreach (range(0, 5) as $i) {
            $slug = $faker->word;
            DB::table('category')->insert([
                'slug' => $slug,
                'created_at' => $faker->dateTime,
                'updated_at' => $faker->dateTime,
            ]);

            $id = DB::table('category')
                ->getConnection()->getPdo()->lastInsertId();

            DB::table('category_translation')->insert([
                'category_id' => $id,
                'language_id' => 1,
                'title' => $slug,//todo translate title
                'language_id' => 1,
                'created_at' => $faker->dateTime,
                'updated_at' => $faker->dateTime,
            ]);
        }
    }
}
