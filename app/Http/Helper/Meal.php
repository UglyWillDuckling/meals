<?php
namespace App\Http\Helper;

use Illuminate\Http\Request;
use App\Meal as MealModel;


class Meal
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
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getResponse()
    {
        $query = $this->generateQuery();

        $result = $query->groupBy('meals.id')
            ->paginate(
                $this->request->perpage,
                $this->getFields(),
                'page',
                $this->request->page
            );
        return $this->generateResponse($result);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function generateQuery()
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
    public function getTags(){
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

    public function getFields(){
        return [
            'meals.id as id',
            'meals.status as status',
            'meals.category_id',
            'mt.description as description',
            'mt.title as title',
        ];
    }

    /**
     * @param $meals
     * @return array
     */
    public function generateResponse($meals)
    {
        //generate links
        $meals->appends(
            $this->request->all()
        );

        //create response array
        return [
            'meta' => [
                'currentPage' => $meals->currentPage(),
                'totalItems' => $meals->total(),
                'itemsPerPage' =>$meals->perPage(),
                'totalPages' =>$meals->lastPage(),
            ],
            'data' => [$meals->getCollection()->toArray()[0]],
            'links' => [
                'prev' => $meals->previousPageUrl(),
                'next' => $meals->nextPageUrl(),
                'self' => $this->request->fullUrl(),
            ]
        ];
    }
}
