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
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function generateQuery(array $fields)
    {
        $request = $this->request;
        $query = MealModel::query();

        if (!empty($fields['lang'])) {
            $this->addLang($fields['lang'], $query);
        }
        if (!empty($fields['diff_time'])) {
            $query->where(
                'meals.updated_at', '>=', date("Y-m-d h:i:s", strtotime($request->diff_time)
            ));
        } else {
            $query->where('status', '=', 1);
        }

        //filter the query
        $this->filter($fields, $query);

        $this->addRelations($query);
        $query->groupBy('meals.id');

        return $query;
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
    public function addRelations($query) {
        $with = explode(',', $this->request->with);
        foreach ($with as $relation) {
            $query->with($relation);
        }
    }
}
