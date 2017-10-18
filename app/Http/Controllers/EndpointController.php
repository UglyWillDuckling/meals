<?php

namespace App\Http\Controllers;

use App\Http\Helper\Meal;
use App\Http\Response\Transform\Api as TransformApi;
use App\Repository\Repositories\MealRepositoryInterface;
use Illuminate\Support\Facades\DB;


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
     * @var MealRepositoryInterface
     */
    private $repository;

    public function __construct(Meal $mealHelper, TransformApi $transformApi, MealRepositoryInterface $repository)
    {
        $this->mealHelper = $mealHelper;
        $this->transformApi = $transformApi;
        $this->repository = $repository;
        DB::enableQueryLog();//todo remove this
    }

    public function index()
    {
        return $this->transformApi->transform(
            $this->mealHelper->getResponse()
        );
    }

    /**
     *
     */
    public function repotest()
    {
        $repository = $this->repository;

        $result = $repository->whereWithTranslationsAdditional([],[
            'tags' => function ($q) {
                $q->where('slug', '=', 'nihil');
            }
        ]);

        dd(DB::getQueryLog());
        dd($result);
    }
}