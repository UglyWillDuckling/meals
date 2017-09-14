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
    public function buildResponse($mealsResult, $fields, $meta = true, $links = true)
    {
        $mealsResult->appends(
            $fields
        );

        /* create response array */

        $response = [
            'data' => $mealsResult->getCollection()->toArray(),
        ];

        if ($meta) {
            $response['meta'] = [
                'currentPage'  => $mealsResult->currentPage(),
                'totalItems'   => $mealsResult->total(),
                'itemsPerPage' => $mealsResult->perPage(),
                'totalPages'   => $mealsResult->lastPage(),
            ];
        }

        if ($links) {
            $response['links'] = [
                'prev' => $mealsResult->previousPageUrl(),
                'next' => $mealsResult->nextPageUrl(),
                'self' => $this->request->fullUrl(),
            ];
        }

        return $response;
    }
}
