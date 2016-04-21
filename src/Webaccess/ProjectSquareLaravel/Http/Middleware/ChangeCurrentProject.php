<?php

namespace Webaccess\ProjectSquareLaravel\Http\Middleware;

use Closure;

class ChangeCurrentProject
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $path = str_replace('/project/', '', $request->getPathInfo());
        $projectID = substr($path, 0, 1);

        if (!$request->session()->has('current_project') || ($request->session()->has('current_project') && $projectID != $request->session()->get('current_project')->id)) {
            if ($project = app()->make('ProjectManager')->getProject($projectID)) {
                $request->session()->set('current_project', $project);
            }
        }

        return $next($request);
    }
}