<?php
namespace App\Http\Helper\Meal;

use Illuminate\Http\Request;
use App\Meal as MealModel;


class QueryBuilder
{
    const   STATUS_ENABLED  = 1;
    const   STATUS_DISABLED = 2;

    protected $table_aliases = [
       'meals_translation' => 'mt'
    ];

    /**
     * @var Request
     */
    private $request;


    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function generateQuery(array $fields)
    {
        /**
         * @var $query \Illuminate\Database\Eloquent\Builder
         */
        $query = MealModel::query();

        if (!empty($fields['lang'])) {
            $this->addLang($fields['lang'], $query);
        }

        if (!empty($fields['diff_time'])) {
            $this->filterTime($query, $fields['diff_time']);
        } else {
            $this->filterStatus(
                $query, self::STATUS_ENABLED);
        }

        //filter the query
        $this->filter($fields, $query);

        $this->addRelations(
            $query, $fields['with'], !empty($fields['lang']));
        $query->groupBy('meals.id');

        return $query;
    }


    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $time
     * @param string $field
     */
    public function filterTime(\Illuminate\Database\Eloquent\Builder $query, $time, $field = 'updated_at')
    {
        $query->where(
            'meals.'.$field, '>=', date("Y-m-d h:i:s", strtotime($time)
        ));
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $status
     */
    public function filterStatus(\Illuminate\Database\Eloquent\Builder $query, $status)
    {
        $query->where('status', '=', $status);
    }

    protected function addLang($lang, $query)
    {
        $query
            ->join('meals_translation as mt', function (\Illuminate\Database\Query\JoinClause $join) use ($lang) {
                $join->on(
                    'mt.meal_id', '=', 'meals.id');
                $join->where(
                    'mt.language_id', '=', $lang);
            });
    }

    public function filter(array $fields, $query)
    {
        foreach ($fields as $field => $value) {
            $decorator =
                __NAMESPACE__ . '\\Filters\\' .
                str_replace(' ', '', ucwords(
                    str_replace('_', ' ', $field)));

            if (class_exists($decorator)) {
                $query = $decorator::filter($query, $value);
            }
        }
    }

    /**
     * @param $query
     */
    public function addRelations(\Illuminate\Database\Eloquent\Builder $query, $with, $lang = false) {
        if(is_string($with)) {
            $with = explode(',', $with);
        }

        foreach ($with as $relation)
        {
            if ($lang) {
                $query->with($relation . 'WithTranslation');
                continue;
            }
            $query->with($relation);
        }
    }

    public function getTableAlias($tableName)
    {
        return $this->table_aliases[$tableName];
    }
}
