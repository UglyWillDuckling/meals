<?php
namespace App\Http\Controllers;

use App\Http\Helper\Meal;
use App\Http\Response\Transform\Api as TransformApi;


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

    public function __construct(Meal $mealHelper, TransformApi $transformApi)
    {
        $this->mealHelper = $mealHelper;
        $this->transformApi = $transformApi;
    }

    public function index()
    {
        return $this->transformApi->transform(
            $this->mealHelper->getResponse()
        );
    }
}
