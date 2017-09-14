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
     * @return array
     */
    public function getResponse()
    {
        $query = $this
            ->queryBuilder->generateQuery($this->request->all());

        $result = $query->paginate(
                $this->request->perpage,
                $this->getFields(),
                'page',
                $this->request->page
        );
        return $this
            ->mealResponse->buildResponse($result, $this->request->all());
    }

    /**
     * @return array
     */
    protected function getFields(){
        $fields = [
            'meals.id as id',
            'meals.status as status',
            'meals.category_id',
        ];

        if ($this->request->lang) {
            $fields[] = MealModel::getTableAlias('meals_translation') . '.description as description';
            $fields[] = MealModel::getTableAlias('meals_translation') . '.title as title';
        }
        return $fields;
    }
}
