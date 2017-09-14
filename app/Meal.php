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

    protected $table = 'meals';

    protected $fillable = [
        'status',
        'category_id',
        'slug'
    ];
    protected $hidden = [
        'category_id'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->request = app()->make('Illuminate\Http\Request');
    }
        /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function tags()
    {
        return $this->belongsToMany(
            'App\Tag', 'meals_tags', 'meal_id', 'tag_id');
    }

    public function tagsWithTranslation()
    {
        return $this->tags()
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
            'App\Ingredient', 'meals_ingredients', 'meal_id', 'ingredient_id');
    }

    public function ingredientsWithTranslation()
    {
        return $this->ingredients()
            ->join('ingredients_translation as tt', function (\Illuminate\Database\Query\JoinClause $join) {
                $join->on('tt.ingredient_id', '=', 'ingredient.id');
                $join->where('tt.language_id', '=', $this->request->lang);
            })->addSelect([
                'ingredient.id as id',
                'tt.title',
                'slug',
            ]);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this
            ->belongsTo('App\Category', 'category_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function categoryWithTranslation()
    {
        return $this->category()
            ->join('category_translation as ct', function (\Illuminate\Database\Query\JoinClause $join) {
                $join->on('ct.category_id', '=', 'category.id');
                $join->where('ct.language_id', '=', $this->request->lang);
            })->addSelect([
                'category.id as id',
                'ct.title',
                'slug',
            ]);
    }
}
