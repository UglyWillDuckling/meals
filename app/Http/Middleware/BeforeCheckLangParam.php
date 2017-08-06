<?php

namespace App\Http\Middleware;

use Closure;

class BeforeCheckLangParam
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->lang) {
            return redirect('error');
        }
        return $next($request);
    }
}