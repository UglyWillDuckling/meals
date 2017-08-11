<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Helper\Meal;


class EndpointController extends Controller
{
    /**
     * @var Meal
     */
    private $mealHelper;

    public function __construct(Meal $mealHelper)
    {
        $this->mealHelper = $mealHelper;
    }

    public function index()
    {
        DB::enableQueryLog();
        $response = $this->mealHelper->getResponse();

        dd( json_encode($response,JSON_FORCE_OBJECT));
//      dd(DB::getQueryLog());
    }
}
