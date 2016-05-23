<?php

namespace Webaccess\ProjectSquareLaravel\Http\Middleware;

use Closure;

class BeforeInstall
{
    public function handle($request, Closure $next)
    {
        if (sizeof(app()->make('UserManager')->getUsers()) == 0) {
            return redirect()->route('install1');
        }

        return $next($request);
    }
}