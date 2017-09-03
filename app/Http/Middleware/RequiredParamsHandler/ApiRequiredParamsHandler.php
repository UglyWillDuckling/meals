<?php
namespace App\Http\Middleware\RequiredParamsHandler;

use Illuminate\Http\Response;


class ApiRequiredParamsHandler extends \App\Http\Middleware\RequiredParamsHandler
{
    protected function handleError()
    {
        return response()
            ->json(['success' => false])
            ->setStatusCode(Response::HTTP_OK);
    }
}
