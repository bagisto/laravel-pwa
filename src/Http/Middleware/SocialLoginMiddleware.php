<?php

namespace Webkul\PWA\Http\Middleware;

use Closure;

class SocialLoginMiddleware
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
        dd("called");
    }
}
