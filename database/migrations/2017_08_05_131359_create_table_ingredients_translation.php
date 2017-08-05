<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableIngredientsTranslation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredients_translation', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ingredient_id');
            $table->unsignedInteger('language_id');

            $table->string('title');

            $table->unique(['ingredient_id', 'language_id']);

            $table->foreign('ingredient_id')->references('id')
                ->on('ingredient')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('language_id')->references('id')
                ->on('language')->onUpdate('cascade')->onDelete('cascade');

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
        //
    }
}
