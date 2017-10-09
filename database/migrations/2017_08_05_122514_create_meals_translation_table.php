<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMealsTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meals_translation', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('meal_id');
            $table->unsignedInteger('language_id');

            $table->string('title');
            $table->text('description');
            //index
            $table->unique(['meal_id', 'language_id']);
            //foreign key
            $table->foreign('meal_id')->references('id')
                ->on('meals')->onDelete('cascade')->onUpdate('cascade');


            $table->foreign('language_id')->references('id')
                ->on('language')->onDelete('cascade')->onUpdate('cascade');
            //foreign key


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('meals_translation');
    }
}
