<?php
namespace App\Repository\Repositories;


use App\Repository\AbstractRepository;
use App\Meal;


class MealRepository extends AbstractRepository implements MealRepositoryInterface
{
    public $table = 'meals';

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    private $query;

    public function __construct(Meal $model)
    {
        parent::__construct($model);
        $this->mealQuery = Meal::query();
        $this->query = app()->make('Illuminate\Database\Eloquent\Builder');;
    }


    public function attachTranslations()
    {
        if (!$meals) {
            return false;
        }

        if(!is_a($meals, 'Illuminate\Database\Eloquent\Collection')) {
            $meals = collect($meals);
        }

        foreach ($meals as $meal) {
            /**
             * @var $meal \Illuminate\Database\Eloquent\Model
             */
            foreach ($relations as $relationAlias) {
                /**
                 * @var \Illuminate\Database\Eloquent\Relations\Relation $relation
                 */
                $relation = $meal->{$relationAlias}();

                $this->_joinTranslationTable($relation, $lang);

                $meal->with($relationAlias);
            }
        }
    }

    private function _joinTranslationTable(//TODO extrapolate this method into a different class
        \Illuminate\Database\Eloquent\Builder $query,
        \Illuminate\Database\Eloquent\Relations\Relation $relation, $meals, $lang)
    {
        //reset wheres
        $query->getQuery()->wheres = [];

        $ids = $meals->pluck('id')->toArray();

        $language_table = $relation->getRelated()->getTranslationTable();
        $language_alias = $relation->getRelated()->getTranslationAlias();
        $related_table = $relation->getModel()->getTable();
        $pivot = $relation->getTable();


        $query->join("{$pivot}", function (\Illuminate\Database\Query\JoinClause $join)
        use($ids, $pivot) {
            $join->on("{$pivot}.tag_id", '=', 'tag.id');
            $join->whereIn("{$pivot}.meals_id", $ids);
        });

        $query->join("{$language_table} as {$language_alias}", function (\Illuminate\Database\Query\JoinClause $join)
        use($language_alias, $lang) {
            $join->on("{$language_alias}.tag_id", '=', 'tag.id');
            $join->where('tt.language_id', '=', $lang);
        });

        $query->rightJoin("{$relation->getTable()} as mtgs", function (\Illuminate\Database\Query\JoinClause $join) use($ids) {
            $join->whereIn('mtgs.meal_id', $ids);
        });

        $query->addSelect([//TODO put the fields from select to different classses
                "{$related_table}.id as id",
                "{$language_alias}.title",
                "{$related_table}.slug"
            ]);

        return $query->get();
    }


    public function getAllWithTranslations($relations = [], $lang = '1')
    {
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $this->query;

        if (!$all = $this->all()) {
            return false;
        }

        foreach ($relations as $index => $relation) {
            if (method_exists($this->getModel(), $relation)) {
                $relation = $this->getModel()->{$relation}();
                $result = $this->_joinTranslationTable($query, $relation, $all, $lang);
            }
            else {
                unset($relations[$index]);
            }
        }
    }


}