<?php
namespace App\Http\Helper;

use Illuminate\Http\Request;


class Meal
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Meal\QueryBuilder
     */
    private $queryBuilder;
    /**
     * @var \App\Http\Response\Transform\Api\MealResponse
     */
    private $mealResponse;


    public function __construct(
        Request $request,
        \App\Http\Helper\Meal\QueryBuilder $queryBuilder,
        \App\Http\Response\Transform\Api\MealResponse $mealResponse)
    {
        $this->request = $request;
        $this->queryBuilder = $queryBuilder;
        $this->mealResponse = $mealResponse;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getResponse()
    {
        $query = $this->queryBuilder->generateQuery($this->request->all());

        $result = $query->paginate(
                $this->request->perpage,
                $this->getFields(),
                'page',
                $this->request->page
            );
        return $this->mealResponse->buildResponse($result);
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
}
