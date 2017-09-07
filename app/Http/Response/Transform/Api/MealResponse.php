<?php
namespace App\Http\Response\Transform\Api;

use App\Http\Response\Transform\Api;
use Illuminate\Http\Request;


class MealResponse extends Api
{
    /**
     * @var Request
     */
    private $request;

    /**
     * MealResponse constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param $meals
     * @return array
     */
    public function buildResponse($meals)
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
            'data' => $meals->getCollection()->toArray(),
            'links' => [
                'prev' => $meals->previousPageUrl(),
                'next' => $meals->nextPageUrl(),
                'self' => $this->request->fullUrl(),
            ]
        ];
    }
}
?>