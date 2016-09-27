<?php

namespace Webaccess\ProjectSquareLaravel\Http\Middleware;

use Closure;

class AfterConfig
{
    public function handle($request, Closure $next)
    {
        if (sizeof(app()->make('UserManager')->getUsers()) > 0) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}