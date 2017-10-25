<?php

namespace App\Http\Controllers;

use App\Http\Helper\Meal;
use App\Http\Response\Transform\Api as TransformApi;
use App\Repository\Repositories\MealRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


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
    public function repotest(Request $request)
    {
        $repository = $this->repository;

        $tagIds = explode(',', $request->tags);

        $result = $repository->whereWithTranslationsAdditional([
            [
                'column' => 'slug',
                'operator' => '=',
                'value' => 'similique'
            ]
        ],[
            'tags' => function ($q) use($tagIds) {
                $q->whereIn($q->getModel()->getTable() . '.id', $tagIds);
            }
        ]);

//        dd(DB::getQueryLog());
        dd($result);
    }
}