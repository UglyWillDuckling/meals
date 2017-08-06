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
    }

    public function index()
    {
        DB::enableQueryLog();
        $meals = $this->getMeals();

        $attrs;
        foreach ($meals as $meal) {
            $meal->setAttribute('tags', $meal->tags());
            $meal->setAttribute('ingredients', $meal->ingredients());
            $attrs = $meal->getAttributes();
        }

        var_dump($attrs);
    }

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
            $tags = explode(',', $request->tags);

            $query
                ->join('meals_tags as mtgs', function (\Illuminate\Database\Query\JoinClause $join) use ($tags) {
                    $join->on('mtgs.meal_id', '=', 'meals.id');
                    $join->whereIn('mtgs.tag_id', $tags);
                });
        }

        return $query;
    }

    private function getMeals()
    {
        $query = $this->generateQuery();

        //check the with paramater
        $with = [
            'meals.id as meal_id',
            'meals.status as status',
            'mt.description as description',
        ];

        $query->groupBy('meals.id');
        return $query->get($with);
    }
}
