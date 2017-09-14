<?php
namespace App\Http\Helper\Meal;

use Illuminate\Http\Request;
use App\Meal as MealModel;


class QueryBuilder
{
    /**
     * @var Request
     */
    private $request;


    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->query  = MealModel::query();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function generateQuery(array $fields)
    {
        /**
         * @var $query \Illuminate\Database\Eloquent\Builder
         */
        $query = $this->query;

        if (!empty($fields['lang'])) {
            $this->addLang($fields['lang'], $query);
        }

        if (!empty($fields['diff_time'])) {
            $this->filterTime(
                $query, $fields['diff_time']);
        } else {
            $this->filterStatus(
                $query, MealModel::STATUS_ENABLED);
        }

        //filter the query
        $this->filter($fields, $query);

        $this->addRelations(
            $query, $fields['with'], !empty($fields['lang']));
        $query->groupBy(
            $query->getModel()->getTable().'.id');

        return $query;
    }


    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $time
     * @param string $field
     */
    protected function filterTime(\Illuminate\Database\Eloquent\Builder $query, $time, $field = 'updated_at')
    {
        $query->where(
            'meals.'.$field, '>=', date("Y-m-d h:i:s", strtotime($time))
        );
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $status
     */
    protected function filterStatus(\Illuminate\Database\Eloquent\Builder $query, $status)
    {
        $query->where('status', '=', $status);
    }

    /**
     * @param $lang
     */
    protected function addLang($lang)
    {
        $this->query
            ->join('meals_translation as ' .
                MealModel::getTableAlias('meals_translation'), function (\Illuminate\Database\Query\JoinClause $join) use ($lang) {
                $join->on(
                    MealModel::getTableAlias('meals_translation') . '.meal_id',
                    '=',
                    $this->query->getModel()->getTable().'.id'
                );
                $join->where(
                    MealModel::getTableAlias('meals_translation') . '.language_id',
                    '=',
                    $lang
                );
            });
    }

    /**
     * filter by given fields using specific filter classes
     *
     * @param array $fields
     *
     *TODO refactor so it uses a default filter when a filter class does not exist
     */
    public function filter(array $fields)
    {
        foreach ($fields as $field => $value) {
            $decorator =
                __NAMESPACE__ . '\\Filters\\' .
                str_replace(' ', '', ucwords(
                    str_replace('_', ' ', $field)));

            if (class_exists($decorator)) {
                $decorator::filter($this->query, $value);
            }
        }
    }

    /**
     * @param $query
     */
    public function addRelations($with, $lang = false) {
        if(is_string($with)) {
            $with = explode(',', $with);
        }

        foreach ($with as $relation)
        {
            if ($lang) {
                $this->query->with($relation . 'WithTranslation');
                continue;
            }
            $this->query->with($relation);
        }
    }
}
