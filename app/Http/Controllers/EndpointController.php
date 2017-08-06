<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Meal;


class EndpointController extends Controller
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->with = $request->with ?: array();
    }

    public function index()
    {
        DB::enableQueryLog();
        $meals = $this->getMeals();

        dd($meals->toArray());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function generateQuery()
    {
        $request = $this->request;
        $query = Meal::query();

        $query
            ->join('meals_translation as mt', function (\Illuminate\Database\Query\JoinClause $join) use($request) {
                $join->on('mt.meal_id', '=', 'meals.id');
                $join->where(
                    'mt.language_id', '=', $request->lang);
             });

        if ($request->tags) {
            $tags = $this->getTags();
            $query
                ->join('meals_tags as mtgs', function (\Illuminate\Database\Query\JoinClause $join) use ($tags) {
                    $join->on('mtgs.meal_id', '=', 'meals.id');
                    $join->whereIn('mtgs.tag_id', $tags);
                });
        }
        return $query;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function getMeals()
    {
        $query = $this->generateQuery();

        $fields = [
            'meals.id as id',
            'meals.status as status',
            'mt.description as description',
        ];
        $query->groupBy('meals.id');

        $query->with('tags');
        $query->with('ingredients');

        return $query->get($fields);
    }

    /**
     * @return array
     */
    private function getTags(){
        return explode(',', $this->request->tags);
    }
}
