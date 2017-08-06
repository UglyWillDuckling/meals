<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Meal extends Model
{
    protected $table = 'meals';

    protected $fillable = [
        'status', 'category_id', 'slug'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function tags()
    {
        return $this->belongsToMany(
            'App\Tag', 'meals_tags', 'meal_id', 'tag_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function ingredients()
    {
        return $this->belongsToMany(
            'App\Ingredient', 'meals_ingredients', 'meal_id', 'ingredient_id');
    }
}
