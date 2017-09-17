<?php
namespace App\Http\Controllers;

use App\Http\Helper\Meal;
use App\Http\Response\Transform\Api as TransformApi;
use App\Repository\RepositoryInterface;
use App\Repository\Repositories\MealRepository;


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

    public function __construct(Meal $mealHelper, TransformApi $transformApi, RepositoryInterface $repository)
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

        $all = $repository->all();

        $all->toArray();
    }
}



