<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTagsTranslation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags_translation', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tag_id');
            $table->unsignedInteger('language_id');

            $table->string('title');

            $table->unique(['tag_id', 'language_id']);

            $table->foreign('tag_id')->references('id')
                ->on('tag')->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('language_id')->references('id')
                ->on('language')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::drop('tags_translation');
    }
}
