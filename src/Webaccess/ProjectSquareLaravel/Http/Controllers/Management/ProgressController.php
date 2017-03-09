<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Management;

use Illuminate\Http\Request;
use Webaccess\ProjectSquare\Requests\Phases\GetPhasesRequest;
use Webaccess\ProjectSquare\Requests\Projects\GetProjectProgressRequest;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;

class ProgressController extends BaseController
{
    public function index(Request $request)
    {
        parent::__construct($request);

        list($projects, $archived_projects) = $this->getProjects();
        foreach ($projects as $project) {
            $project->phases = app()->make('GetPhasesInteractor')->execute(new GetPhasesRequest([
                'projectID' => $project->id
            ]));
            $project->progress = app()->make('GetProjectProgressInteractor')->execute(new GetProjectProgressRequest([
                'projectID' => $project->id,
                'phases' => $project->phases
            ]));
        }

        return view('projectsquare::management.progress.index', [
            'projects' => $projects
        ]);
    }
}