<?php

namespace App\Http\Middleware;

use Closure;

abstract class RequiredParamsHandler
{
    /***
     *Check to see if the request contains the necessary params
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$required)
    {
        if (!$request->has($required)) {
            return $this->handleError();
        }
        return $next($request);
    }

    protected function handleError()
    {
        return \response()->redirect('/error');
    }
}
