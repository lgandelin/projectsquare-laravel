<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Webaccess\ProjectSquare\Requests\Phases\GetPhasesRequest;
use Webaccess\ProjectSquare\Requests\Planning\GetEventsRequest;
use Webaccess\ProjectSquare\Requests\Projects\GetProjectProgressRequest;
use Webaccess\ProjectSquare\Requests\Tasks\GetTasksRequest;
use Webaccess\ProjectSquare\Requests\Todos\GetTodosRequest;
use Webaccess\ProjectSquare\Requests\Calendar\GetStepsRequest;

class DashboardController extends BaseController
{
    public function index(Request $request)
    {
        parent::__construct($request);

        $this->initWidgetsIfNecessary();

        if ($this->isUserAClient()) {
            return redirect()->route('project_tickets', $this->getCurrentProject()->id);
        }

        return view('projectsquare::dashboard.index', [
            'widgets' => json_decode($_COOKIE['dashboard-widgets-' . $this->getUser()->id]),
            'tasks' => app()->make('GetTasksInteractor')->getTasksPaginatedList(
                $this->getUser()->id,
                env('TASKS_PER_PAGE', 10),
                null,
                null,
                new GetTasksRequest([
                    'allocatedUserID' => $this->getUser()->id,
                    'phaseID' => false
                ])
            ),
            'tickets' => app()->make('GetTicketInteractor')->getTicketsPaginatedList(
                $this->getUser()->id,
                env('TICKETS_PER_PAGE', 10),
                null,
                $this->getUser()->id
            ),
            'events' => app()->make('GetEventsInteractor')->execute(new GetEventsRequest([
                'userID' => $this->getUser()->id,
            ])),
            'todos' => app()->make('GetTodosInteractor')->execute(new GetTodosRequest([
                'userID' => $this->getUser()->id,
            ])),
            'steps' => ($this->getCurrentProject()) ? app()->make('GetStepsInteractor')->execute(new GetStepsRequest([
                'projectID' => $this->getCurrentProject()->id,
            ])) : [],
            'current_projects_reporting' => $this->isUserAnAdmin() ? $this->getCurrentProjectReporting() : [],
        ]);
    }

    private function initWidgetsIfNecessary()
    {
        if ($this->isUserAnAdmin()) {
            $widgets[]= ['name' => 'reporting', 'width' => 12];
        }

        $widgets[]= ['name' => 'tickets', 'width' => 6];

        if (!$this->isUserAClient()) {
            $widgets[]= ['name' => 'tasks', 'width' => 6];
            $widgets[]= ['name' => 'planning', 'width' => 12];
        } else {
            //$widgets[]= ['name' => 'calendar', 'width' => 6];
        }

        if (!isset($_COOKIE['dashboard-widgets-' . $this->getUser()->id])) {
            $_COOKIE['dashboard-widgets-' . $this->getUser()->id] = json_encode($widgets);
        }
    }

    private function getCurrentProjectReporting()
    {
        list($projects, $archived_projects) = $this->getProjects();
        foreach ($projects as $project) {
            $project->phases = app()->make('GetPhasesInteractor')->execute(new GetPhasesRequest([
                'projectID' => $project->id
            ]));
            $project->progress = app()->make('GetProjectProgressInteractor')->execute(new GetProjectProgressRequest([
                'projectID' => $project->id,
                'phases' => $project->phases
            ]));
            $project->differenceSpentEstimated = 0;

            foreach ($project->phases as $phase) {
                $project->differenceSpentEstimated += $phase->differenceSpentEstimated;
            }
        }

        return $projects;
    }
}
