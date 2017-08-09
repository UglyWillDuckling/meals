<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Helper\Meal;


class EndpointController extends Controller
{
    /**
     * @var Meal
     */
    private $meal;

    public function __construct(Meal $meal)
    {
        $this->meal = $meal;
    }

    public function index()
    {
        DB::enableQueryLog();
        $response = $this->meal->getResponse();

        dd(($response));
//      dd(DB::getQueryLog());
    }
}
