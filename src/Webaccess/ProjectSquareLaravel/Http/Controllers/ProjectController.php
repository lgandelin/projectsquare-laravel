<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Webaccess\ProjectSquare\Requests\Phases\GetPhasesRequest;
use Webaccess\ProjectSquare\Requests\Projects\GetProjectProgressRequest;
use Webaccess\ProjectSquare\Requests\Tasks\GetTaskRequest;
use Webaccess\ProjectSquare\Requests\Tasks\GetTasksRequest;
use Webaccess\ProjectSquareLaravel\Http\Controllers\Tools\TaskController;

class ProjectController extends BaseController
{
    public function index(Request $request)
    {
        parent::__construct($request);

        return view('projectsquare::project.cms', [
            'project' => app()->make('ProjectManager')->getProject($request->uuid),
        ]);
    }

    public function tasks(Request $request)
    {
        parent::__construct($request);

        $projectID = $request->uuid;

        $request->session()->put('tasks_interface', 'project');

        if (Input::get('filter_status') !== null) Session::put('project_tasks_filter_status', Input::get('filter_status'));
        if (Input::get('filter_allocated_user') !== null) Session::put('project_tasks_filter_allocated_user', Input::get('filter_allocated_user'));
        if (Input::get('filter_phase') !== null) Session::put('project_tasks_filter_phase', Input::get('filter_phase'));

        return view('projectsquare::project.tasks', [
            'project' => app()->make('ProjectManager')->getProject($projectID),
            'phases' => app()->make('GetPhasesInteractor')->execute(new GetPhasesRequest([
                'projectID' => $projectID
            ])),
            'users' => app()->make('UserManager')->getUsersByProject($projectID),
            'task_statuses' => TaskController::getTasksStatuses(),
            'filters' => [
                'allocated_user' => Session::get('project_tasks_filter_allocated_user'),
                'status' => Session::get('project_tasks_filter_status'),
                'phase' => Session::get('project_tasks_filter_phase'),
            ],
            'other_tasks' => app()->make('GetTasksInteractor')->getTasksPaginatedList($this->getUser()->id, 999, null, null, new GetTasksRequest([
                'projectID' => $projectID,
                'statusID' => Session::get('project_tasks_filter_status') === "na" ? null : Session::get('project_tasks_filter_status'),
                'phaseID' => null,
                'allocatedUserID' => Session::get('project_tasks_filter_allocated_user') === "na" ? null : Session::get('project_tasks_filter_allocated_user'),
            ])),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function tasks_edit(Request $request)
    {
        parent::__construct($request);

        $taskID = $request->task_uuid;
        $projectID = $request->uuid;

        try {
            $task = app()->make('GetTaskInteractor')->execute(new GetTaskRequest([
                'taskID' => $taskID,
                'requesterUserID' => $this->getUser()->id,
            ]));
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());

            return redirect()->route('tasks_index');
        }

        if (!$task) {
            $request->session()->flash('error', trans('projectsquare::tasks.task_not_found'));

            return redirect()->route('tasks_index');
        }

        return view('projectsquare::project.tasks.edit', [
            'task' => $task,
            'other_tasks' => app()->make('GetTasksInteractor')->getTasksPaginatedList($this->getUser()->id, 999, null, null, new GetTasksRequest([
                'projectID' => $projectID,
                'statusID' => Session::get('project_tasks_filter_status') === "na" ? null : Session::get('project_tasks_filter_status'),
                'phaseID' => null,
                'allocatedUserID' => Session::get('project_tasks_filter_allocated_user') === "na" ? null : Session::get('project_tasks_filter_allocated_user'),
            ])),
            'project' => app()->make('GetProjectInteractor')->getProject($projectID),
            'phases' => app()->make('GetPhasesInteractor')->execute(new GetPhasesRequest([
                'projectID' => $projectID
            ])),
            'task_statuses' => TaskController::getTasksStatuses(),
            'users' => app()->make('UserManager')->getUsersByProject($task->projectID),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function tickets(Request $request)
    {
        parent::__construct($request);

        $projectID = $request->uuid;
        $request->session()->put('tickets_interface', 'project');

        return view('projectsquare::project.tickets', [
            'project' => app()->make('ProjectManager')->getProject($projectID),
            'tickets_grouped_by_states' => $this->getTicketGroupedByStates($projectID),
            'users' => app()->make('UserManager')->getUsersByProject($projectID),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function tickets_edit(Request $request)
    {
        parent::__construct($request);

        $ticketID = $request->ticket_uuid;
        $projectID = $request->uuid;

        try {
            $ticket = app()->make('GetTicketInteractor')->getTicketWithStates($ticketID, $this->getUser()->id);
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());

            return redirect()->route('tickets_index');
        }

        if (!$ticket) {
            $request->session()->flash('error', trans('projectsquare::tickets.ticket_not_found'));

            return redirect()->route('tickets_index');
        }

        return view('projectsquare::project.tickets.edit', [
            'tickets_grouped_by_states' => $this->getTicketGroupedByStates($projectID),
            'ticket' => $ticket,
            'project' => app()->make('GetProjectInteractor')->getProject($projectID),
            'ticket_states' => app()->make('GetTicketInteractor')->getTicketStatesPaginatedList($ticketID, env('TICKET_STATES_PER_PAGE', 10)),
            'ticket_types' => app()->make('TicketTypeManager')->getTicketTypes(),
            'ticket_status' => app()->make('TicketStatusManager')->getTicketStatuses(),
            'users' => app()->make('UserManager')->getUsersByProject($ticket->projectID),
            'files' => app()->make('FileManager')->getFilesByTicket($ticketID),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }
    
    public function monitoring(Request $request)
    {
        parent::__construct($request);

        $projectID = $request->uuid;

        $requests = app()->make('RequestManager')->getRequestsByProject($projectID)->get();

        return view('projectsquare::project.monitoring', [
            'project' => app()->make('ProjectManager')->getProject($projectID),
            'average_loading_time' => app()->make('RequestManager')->getAverageLoadingTime($requests),
            'availability_percentage' => (count($requests) > 0) ? app()->make('RequestManager')->getAvailabilityPercentage($projectID) : null,
            'status_codes' => (count($requests) > 0) ? app()->make('RequestManager')->getStatusCodes($projectID) : null,
            'loading_times' => (count($requests) > 0) ? app()->make('RequestManager')->getLoadingTimes($projectID) : null,
            'max_loading_time' => app()->make('RequestManager')->getMaxLoadingTimeByProject($projectID),
            'requests' => app()->make('RequestManager')->formatDataForGraphs($requests),
        ]);
    }

    public function messages(Request $request)
    {
        parent::__construct($request);

        $projectID = $request->uuid;

        $request->session()->put('messages_interface', 'project');

        return view('projectsquare::project.messages', [
            'project' => app()->make('ProjectManager')->getProject($projectID),
            'conversations' => app()->make('ConversationManager')->getConversationsByProject($projectID),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function seo(Request $request)
    {
        parent::__construct($request);

        $projectID = $request->uuid;

        $gaViewID = app()->make('SettingManager')->getSettingByKeyAndProject('GA_VIEW_ID', $projectID);

        return view('projectsquare::project.seo', [
            'project' => app()->make('ProjectManager')->getProject($projectID),
            'gaViewID' => ($gaViewID) ? $gaViewID->value : null,
            'startDate' => (new Carbon())->addDay(-30)->format('d/m/Y'),
            'endDate' => (new Carbon())->format('d/m/Y'),
        ]);
    }

    public function get_users()
    {
        try {
            $users = (Input::get('project_id')) ? app()->make('UserManager')->getUsersByProject(Input::get('project_id')) : app()->make('UserManager')->getAgencyUsers();

            return response()->json($users, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function progress(Request $request)
    {
        parent::__construct($request);

        $projectID = $request->uuid;

        if ($project = app()->make('ProjectManager')->getProject($projectID)) {
            $project->phases = app()->make('GetPhasesInteractor')->execute(new GetPhasesRequest([
                'projectID' => $project->id
            ]));
            $project->progress = app()->make('GetProjectProgressInteractor')->execute(new GetProjectProgressRequest([
                'projectID' => $project->id,
                'phases' => $project->phases
            ]));

            return view('projectsquare::project.progress', [
                'project' => $project,
            ]);
        }

        return redirect()->route('dashboard');
    }

    public function spent_time(Request $request)
    {
        parent::__construct($request);

        $projectID = $request->uuid;

        if ($project = app()->make('ProjectManager')->getProject($projectID)) {
            $project->phases = app()->make('GetPhasesInteractor')->execute(new GetPhasesRequest([
                'projectID' => $project->id
            ]));
            $project->differenceSpentEstimated = 0;
            foreach ($project->phases as $phase) {
                $project->differenceSpentEstimated += $phase->differenceSpentEstimated;
            }

            return view('projectsquare::project.spent_time', [
                'project' => $project,
            ]);
        }

        return redirect()->route('dashboard');
    }

    public function client_switch(Request $request)
    {
        parent::__construct($request);

        $projectID = $request->get('project_id');

        try {
            if ($project = app()->make('ProjectManager')->getProject($projectID)) {
                $this->request->session()->put('current_project', $project);
            }
        } catch(\Exception $e) {

        }

        return redirect()->route($request->get('route') ? $request->get('route') : 'dashboard', ['uuid' => $projectID]);
    }

    /**
     * @param $projectID
     * @return mixed
     */
    private function getTicketGroupedByStates($projectID)
    {
        $states = app()->make('TicketStatusManager')->getTicketStatuses();
        foreach ($states as $state) {
            $state->tickets = app()->make('GetTicketInteractor')->getTicketsPaginatedList(
                $this->getUser()->id,
                999,
                $projectID,
                null,
                $state->id,
                null
            );
        }
        return $states;
    }
}
