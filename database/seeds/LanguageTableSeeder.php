<?php

use Illuminate\Database\Seeder;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            'hr_HR' => 'hrvatski',
            'en_us' => 'english',
            'de_DE' => 'deutsch',
            'fr_FR' => 'francais',
            'es_ES' => 'espanol',
        ];
        $faker = Faker\Factory::create();
        foreach ($languages as $key => $lang) {
            DB::table('language')->insert([
                'slug' => $key,
                'name' => $lang,
                'created_at' => $faker->dateTime,
                'updated_at' => $faker->dateTime,
            ]);
        }
    }
}
