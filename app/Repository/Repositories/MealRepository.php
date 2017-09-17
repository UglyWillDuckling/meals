<?php
namespace App\Repository\Repositories;


use App\Repository\AbstractRepository;
use App\Meal;


class MealRepository extends AbstractRepository implements MealRepositoryInterface
{
    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    private $query;

    public function __construct(\Illuminate\Database\Eloquent\Model $model)
    {
        parent::__construct($model);
        $this->query = Meal::query();
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

    private function _joinTranslationTable(
        \Illuminate\Database\Eloquent\Builder $query,
        \Illuminate\Database\Eloquent\Relations\Relation $relation, $lang)
    {
        $language_table = $relation->getRelated()->getTranslationTable();
        $language_alias = $relation->getRelated()->getTranslationAlias();
        $related_table = $relation->getRelated()->getTable();

        $relation->join("{$language_table} as {$language_alias}", function (\Illuminate\Database\Query\JoinClause $join)
        use($language_alias, $lang) {
            $join->on("{$language_alias}.tag_id", '=', 'tag.id');
            $join->where('tt.language_id', '=', $lang);
        })->addSelect([
            "{$related_table}.id as id",
            "{$language_alias}.title",
            'slug',
        ]);

        return $relation;
    }


    public function getAllWithTranslations($relations = [], $lang = 'HR')
    {

        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $this->query;


        foreach ($relations as $index => $relation) {
            if (method_exists($this->getModel(), $relation)) {
                $relation = $this->getModel()->{$relation}();
                $relation = $this->_joinTranslationTable($relation, $lang);
            }
            else {
                unset($relations[$index]);
            }
        }
        return $query->with($relations)->get();

    }


}