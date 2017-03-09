<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Management;

use Illuminate\Http\Request;
use Webaccess\ProjectSquare\Requests\Phases\GetPhasesRequest;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;

class SpentTimeController extends BaseController
{
    public function index(Request $request)
    {
        parent::__construct($request);

        list($projects, $archived_projects) = $this->getProjects();
        foreach ($projects as $project) {
            $project->phases = app()->make('GetPhasesInteractor')->execute(new GetPhasesRequest([
                'projectID' => $project->id
            ]));

            $project->differenceSpentEstimated = 0;
            foreach ($project->phases as $phase) {
                $project->differenceSpentEstimated += $phase->differenceSpentEstimated;
            }
        }

        return view('projectsquare::management.spent_time.index', [
            'projects' => $projects
        ]);
    }
}