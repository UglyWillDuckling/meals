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
        $response = $this->getResponse();

        dd(($response));
//      dd(DB::getQueryLog());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    protected function getResponse()
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
    private function generateQuery()
    {
        $request = $this->request;
        $query = Meal::query();

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
    private function getTags(){
        return explode(',', $this->request->tags);
    }

    /**
     * @param $query
     */
    private function addRelations($query) {
        $with = explode(',', $this->request->with);
        foreach ($with as $relation) {
            $query->with($relation);
        }
    }

    private function getFields(){
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
    private function generateResponse($meals)
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
            'data' => [$meals->getCollection()->toArray()],
            'links' => [
                'prev' => $meals->previousPageUrl(),
                'next' => $meals->nextPageUrl(),
                'self' => $this->request->fullUrl(),
            ]
        ];
    }
}
