<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    const TABLE_TRANSLATION = 'ingredients_translation';
    const TABLE_TRANSLATION_ALIAS = 'it';
    const PIVOT_FOREIGN_KEY = 'ingredient_id';

    protected $table = 'ingredient';
    protected $hidden = [
        'pivot'
    ];

    protected $fillable = [
        'slug'
    ];


    public function getTranslationTable()
    {
        return self::TABLE_TRANSLATION;
    }
    public function getTranslationAlias()
    {
        return self::TABLE_TRANSLATION_ALIAS;
    }

    public function getMealsForeignKey()
    {
        return self::PIVOT_FOREIGN_KEY;
    }
}
