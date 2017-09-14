<?php

namespace Webaccess\ProjectSquareLaravel\Http\Middlewares;

use Closure;

class BeforeConfig
{
    public function handle($request, Closure $next)
    {
        if (sizeof(app()->make('UserManager')->getUsers()) == 0) {
            return redirect()->route('config');
        }

        return $next($request);
    }
}