<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Utility;

use Illuminate\Http\Request;
use Webaccess\ProjectSquare\Requests\Phases\GetPhasesRequest;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;

class ProgressController extends BaseController
{
    public function index(Request $request)
    {
        parent::__construct($request);

        $user = $this->getUserWithProjects();
        $projects = $user->projects;
        foreach ($projects as $project) {
            $project->phases = app()->make('GetPhasesInteractor')->execute(new GetPhasesRequest([
                'projectID' => $project->id
            ]));
        }

        return view('projectsquare::progress.index', [
            'projects' => $projects
        ]);
    }
}