<?php
namespace App\Repository\Repositories;


use App\Repository\AbstractRepository;
use App\Meal;
use Illuminate\Support\Facades\DB;

use function foo\func;


class MealRepository extends AbstractRepository implements MealRepositoryInterface
{
    public $table = 'meals';

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    private $query;

    public function attachTranslations($meals)
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

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Relations\Relation $relation
     * @param $meals
     * @param $lang
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function _joinTranslationTable(//TODO extrapolate this method into a different class
        \Illuminate\Database\Eloquent\Builder $query,
        \Illuminate\Database\Eloquent\Relations\Relation $relation, $meals, $lang)
    {
        //reset the query
        $query->getQuery()->wheres = [];
        $query->getQuery()->columns = [];
        $query->getQuery()->joins = [];
        //reset the query

        $ids = $meals->pluck('id')->toArray();
        $relatedModel = $relation->getModel();

        $language_table = $relation->getRelated()->getTranslationTable();
        $language_alias = $relation->getRelated()->getTranslationAlias();
        $related_table = $relation->getModel()->getTable();
        $pivot = $relation->getTable();

        //set the model for the query based on the $relation parameter
        $query->setModel($relatedModel);


        $query->join("{$pivot}", function (\Illuminate\Database\Query\JoinClause $join)
        use($ids, $pivot, $related_table, $relatedModel) {
            $join->on("{$pivot}.{$relatedModel->getForeignKey()}", '=', "{$related_table}.id");
            $join->whereIn("{$pivot}.{$this->getModel()->getForeignKey()}", $ids);
        });

        $query->join("{$language_table} as {$language_alias}", function (\Illuminate\Database\Query\JoinClause $join)
        use($language_alias, $lang, $relatedModel,$related_table) {
            $join->on("{$language_alias}.{$relatedModel->getForeignKey()}", '=', "{$related_table}.id");
            $join->where("{$language_alias}.language_id", '=', $lang);
        });

        $query->select = [];

        $query->addSelect([//TODO put the fields from select to different classses
                "{$related_table}.id as id",
                "{$language_alias}.title",
                "{$related_table}.slug",
                "{$pivot}.meal_id"
            ]);
        return $query->get();
    }


    /**
     * @param array $relations
     * @param string $lang
     * @return array|bool
     */
    public function getAllWithTranslations($relations = [], $lang = '1')//TODO rewrite this so it only gets the data for the meals
    {
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $this->query;

        if (!$all = $this->all()) {
            return false;
        }

        $result = [];
        foreach ($relations as $index => $relation) {
            if (method_exists($this->getModel(), $relation)) {
                $relation = $this->getModel()->{$relation}();
                $result[$relation->getRelationName()] = $this->_joinTranslationTable($query, $relation, $all, $lang);
            }
        }
        $result = $this->_joinResultsToModels($result, $all);
        return $result;
    }


    /**
     * @param array $results
     * @param $models
     * @param string $foreign_key
     * @return mixed
     */
    protected function _joinResultsToModels(array $results, $models, $foreign_key = 'meal_id')
    {
        foreach ($results as $relation => $result) {
            foreach ($result as $row) {
                foreach ($models as $parentItem) {
                    if ($parentItem->id == $row->{$foreign_key}) {
                        $parentItem->setAttribute($relation, $row);
                    }
                }
            }
        }
        return $models;
    }


    /**
     * @param array $conditions
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function whereWithTranslations(array $conditions)
    {
        return $this->_where($conditions, '*', [], [
            'table' => 'meals_translation',
            'table_alias' => Meal::getTableAlias('meals_translation'),
            'operator' => '='
        ]);
    }

    /**
     * @param array $conditions
     * @param array $relationalConditions
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function whereWithTranslationsAdditional(array $conditions = [], array $relationalConditions = [])
    {
        $query = $this->getQuery();

        $relations = [
            'tags',
            'ingredients',
        ];

        foreach ($relations as $relation) {
            if (array_key_exists($relation, $relationalConditions) && is_callable($relationalConditions[$relation])) {
                //addd the callback to the relation
                $query->whereHas($relation, $relationalConditions[$relation]);
            }
        }

        //attach the translation relationships
        $query->with(array_map(function ($value) {
            return $value . 'WithTranslation';
        }, $relations));

        return $query->get();
    }
}
