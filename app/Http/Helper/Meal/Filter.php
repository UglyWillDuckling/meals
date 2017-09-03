<?php
namespace App\Http\Helper\Meal;

use Illuminate\Http\Request;
use App\Meal as MealModel;


class Filter
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
    public function generateQuery()//refactor this so it accepts parameters
    {
        $request = $this->request;
        $query = MealModel::query();

        $query
            ->join('meals_translation as mt', function (\Illuminate\Database\Query\JoinClause $join) use ($request) {
                $join->on(
                    'mt.meal_id', '=', 'meals.id');
                $join->where(
                    'mt.language_id', '=', $request->lang);
            });

        if ($request->tags) {
            $tags = $this->getTags();
            $query
                ->join('meals_tags as mtgs', function (\Illuminate\Database\Query\JoinClause $join) use ($tags) {
                    $join->on(
                        'mtgs.meal_id', '=', 'meals.id');
                    $join->whereIn(
                        'mtgs.tag_id', $tags);
                });
        }
        if ($request->category) {
            $query->where('category_id', '=', $request->category);
        }
        if ($request->diff_time) {
            $query->where(
                'meals.updated_at', '>=', date("Y-m-d h:i:s", strtotime($request->diff_time)
            ));
        } else {
            $query->where('status', '=', 1);
        }

        $this->addRelations($query);
        $query->groupBy('meals.id');

        return $query;
    }

    /**
     * @return array
     */
    protected function getTags(){
        return explode(',', $this->request->tags);
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
