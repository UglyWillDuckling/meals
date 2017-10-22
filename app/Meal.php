<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Grammars\MySqlGrammar;
use Illuminate\Http\Request;
use App\Registry\Registry;
use App\Laravel\Eloquent\Query\Builder;

class Meal extends Model
{
    const   STATUS_DISABLED = 2;
    const   STATUS_ENABLED  = 1;

    public static $table_aliases = [
        'meals_translation' => 'mt',
        'meals_tags' => 'mtgs'
    ];

    protected $defaultLanguage = '1';

    /**
     * @var Request
     */
    protected $request;

    protected $lang;

    protected $table = 'meals';

    protected $fillable = [
        'status',
        'category_id',
        'slug'
    ];
    protected $hidden = [
        'category_id'
    ];
    /**
     * @var $registry Registry
     */
    private $registry;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->request = app()->make('Illuminate\Http\Request');
        $this->registry = app()->make('App\Registry\Registry');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(
            'App\Tag', 'meals_tags', 'meal_id', 'tag_id');
    }

    public function tagsWithTranslation(){
        $_this = $this;
        $relation =  $this->tags()
            ->join('tags_translation as tt', function (\Illuminate\Database\Query\JoinClause $join) use($_this){
                $join->on('tt.tag_id', '=', 'tag.id');
                $join->where('tt.language_id', '=', $_this->getLanguage());
            })->addSelect([
                'tag.id as id',
                'tt.title',
                'slug',
            ]);
        return $relation;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ingredients()
    {
        return $this->belongsToMany(
            'App\Ingredient', 'meals_ingredients', 'meal_id', 'ingredient_id');
    }

    public function ingredientsWithTranslation(){
        $_this = $this;
        return $this->ingredients()
            ->join('ingredients_translation as it', function (\Illuminate\Database\Query\JoinClause $join) use($_this){
                $join->on('it.ingredient_id', '=', 'ingredient.id');
                $join->where('it.language_id', '=', $_this->getLanguage());
            })->addSelect([
                'ingredient.id as id',
                'it.title',
                'slug',
            ]);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(){
        return $this
            ->belongsTo('App\Category', 'category_id');
    }


    public function categoryWithTranslation(){
        $_this = $this;
        $relation =  $this->category()
            ->join('category_translation as ct', function (\Illuminate\Database\Query\JoinClause $join) use($_this) {
                $join->on('ct.category_id', '=', 'category.id');
                $join->where('ct.language_id', '=', $_this->getLanguage());
            })->addSelect([
                'category.id as id',
                'ct.title',
                'slug',
            ]);
        return $relation;
    }

    /**
     * @param $alias
     * @return bool|mixed
     */
    public static function getTableAlias($alias)
    {
        return isset(self::$table_aliases[$alias]) ? self::$table_aliases[$alias] : false;
    }

    public function getLanguage(){
        if (!$this->lang) {
            //get the language from the registry or return the default
            if (!$this->lang = $this->registry->currentLanguage) {
                $this->lang = $this->defaultLanguage;
            }
        }
        return $this->lang;
    }

    public function newEloquentBuilder($query)
    {
        $builder =  new Builder($query);
        $builder->getQuery()->grammar = new MySqlGrammar();
        return $builder;
    }
}
