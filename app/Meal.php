<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class Meal extends Model
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->request = app()->make('Illuminate\Http\Request');
    }

    protected $table = 'meals';

    protected $fillable = [
        'status',
        'category_id',
        'slug'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function tags()
    {
        return $this->belongsToMany(
            'App\Tag', 'meals_tags', 'meal_id', 'tag_id')
            ->join('tags_translation as tt', function (\Illuminate\Database\Query\JoinClause $join) {
                $join->on('tt.tag_id', '=', 'tag.id');
                $join->where('tt.language_id', '=', $this->request->lang);
            })->addSelect([
                'tag.id as id',
                'tt.title',
                'slug',
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function ingredients()
    {
        return $this->belongsToMany(
            'App\Ingredient', 'meals_ingredients', 'meal_id', 'ingredient_id')
            ->join('ingredients_translation as tt', function (\Illuminate\Database\Query\JoinClause $join) {
                $join->on('tt.ingredient_id', '=', 'ingredient.id');
                $join->where('tt.language_id', '=', $this->request->lang);
            })->addSelect([
                'ingredient.id as id',
                'tt.title',
                'slug',
            ]);
    }
}
