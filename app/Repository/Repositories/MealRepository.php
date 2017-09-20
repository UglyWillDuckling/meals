<?php
namespace App\Repository\Repositories;


use App\Repository\AbstractRepository;
use App\Meal;
use Illuminate\Database\Query\Grammars\MySqlGrammar;


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
        $this->query->getQuery()->grammar = new MySqlGrammar();
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
        //reset wheres
        $query->getQuery()->wheres = [];

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
            $join->on("{$pivot}.{$relatedModel->getForeignKey()}", '=', "{$related_table}.id");//todo store  the foreign key columns somewhere
            $join->whereIn("{$pivot}.{$this->getModel()->getForeignKey()}", $ids);
        });

        $query->join("{$language_table} as {$language_alias}", function (\Illuminate\Database\Query\JoinClause $join)
        use($language_alias, $lang, $relatedModel) {
            $join->on("{$language_alias}.{$relatedModel->getForeignKey()}", '=', 'tag.id');
            $join->where("{$language_alias}.language_id", '=', $lang);
        });

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
    public function getAllWithTranslations($relations = [], $lang = '1')
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

    protected function _joinResultsToModels($results, $models)
    {

    }
}