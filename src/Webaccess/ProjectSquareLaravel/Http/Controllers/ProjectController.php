<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Entities\Task;
use Webaccess\ProjectSquare\Requests\Tasks\GetTasksRequest;
use Webaccess\ProjectSquareLaravel\Http\Controllers\Tools\TaskController;
use Webaccess\ProjectSquareLaravel\Tools\FilterTool;

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

        $tasks = app()->make('GetTasksInteractor')->getTasksPaginatedList($this->getUser()->id, env('TASKS_PER_PAGE', 10), new GetTasksRequest([
            'projectID' => $projectID,
            'statusID' => Input::get('filter_status'),
            'allocatedUserID' => Input::get('filter_allocated_user'),
        ]));

        return view('projectsquare::project.tasks', [
            'project' => app()->make('ProjectManager')->getProject($projectID),
            'projects' => app()->make('ProjectManager')->getProjects(),
            'users' => app()->make('UserManager')->getUsersByProject($projectID),
            'task_statuses' => TaskController::getTasksStatuses(),
            'filters' => [
                'allocated_user' => Input::get('filter_allocated_user'),
                'status' => Input::get('filter_status'),
                'type' => Input::get('filter_type'),
            ],
            'tasks' => Input::get('filter_status') ? $tasks : FilterTool::filterTaskList($tasks),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function tickets(Request $request)
    {
        parent::__construct($request);

        $projectID = $request->uuid;

        $request->session()->put('tickets_interface', 'project');

        $tickets = app()->make('GetTicketInteractor')->getTicketsPaginatedList(
            $this->getUser()->id,
            env('TICKETS_PER_PAGE', 10),
            $projectID,
            Input::get('filter_allocated_user'),
            Input::get('filter_status'),
            Input::get('filter_type')
        );

        return view('projectsquare::project.tickets', [
            'project' => app()->make('ProjectManager')->getProject($projectID),
            'projects' => app()->make('ProjectManager')->getProjects(),
            'users' => app()->make('UserManager')->getUsersByProject($projectID),
            'ticket_statuses' => app()->make('TicketStatusManager')->getTicketStatuses(),
            'ticket_types' => app()->make('TicketTypeManager')->getTicketTypes(),
            'filters' => [
                'allocated_user' => Input::get('filter_allocated_user'),
                'status' => Input::get('filter_status'),
                'type' => Input::get('filter_type'),
            ],
            'tickets' => Input::get('filter_status') ? $tickets : FilterTool::filterTicketList($tickets),
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

    protected function filterTaskList($tasks)
    {
        foreach ($tasks as $i => $task) {

            //Remove completed tasks
            if ($task->status_id == Task::COMPLETED)
                unset($tasks[$i]);
        }

        return $tasks;
    }
}
