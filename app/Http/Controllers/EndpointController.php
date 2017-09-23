<?php
namespace App\Http\Controllers;

use App\Http\Helper\Meal;
use App\Http\Response\Transform\Api as TransformApi;
use App\Repository\RepositoryInterface;
use App\Repository\Repositories\MealRepositoryInterface;


class EndpointController extends Controller
{
    /**
     * @var Meal
     */
    private $mealHelper;
    /**
     * @var Transform
     */
    private $transformApi;
    /**
     * @var RepositoryInterface
     */
    private $repository;

    public function __construct(Meal $mealHelper, TransformApi $transformApi, MealRepositoryInterface $repository)
    {
        $this->mealHelper = $mealHelper;
        $this->transformApi = $transformApi;
        $this->repository = $repository;
    }

    public function index()
    {
        return $this->transformApi->transform(
            $this->mealHelper->getResponse()
        );
    }

    public function repotest()
    {
        $repository = $this->repository;


        $result = $repository->where([
            [
                'column' => 'status',
                'operator' => '=',
                'value' => 3
            ]
        ]);


        dd($result);
    }
}



