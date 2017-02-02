<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Entities\Task;
use Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\TaskController;
use Webaccess\ProjectSquare\Requests\Tasks\GetTasksRequest;

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
            'tasks' => app()->make('GetTasksInteractor')->getTasksPaginatedList($this->getUser()->id, env('TASKS_PER_PAGE', 10), new GetTasksRequest([
                'projectID' => $projectID,
                'statusID' => Input::get('filter_status'),
                'allocatedUserID' => Input::get('filter_allocated_user'),
            ])),
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
            'projects' => app()->make('ProjectManager')->getProjects(),
            'users' => app()->make('UserManager')->getUsersByProject($projectID),
            'ticket_statuses' => app()->make('TicketStatusManager')->getTicketStatuses(),
            'ticket_types' => app()->make('TicketTypeManager')->getTicketTypes(),
            'filters' => [
                'allocated_user' => Input::get('filter_allocated_user'),
                'status' => Input::get('filter_status'),
                'type' => Input::get('filter_type'),
            ],

            'tickets' => app()->make('GetTicketInteractor')->getTicketsPaginatedList(
                $this->getUser()->id,
                env('TICKETS_PER_PAGE', 10),
                $projectID,
                Input::get('filter_allocated_user'),
                Input::get('filter_status'),
                Input::get('filter_type')
            ),
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

    public function reporting(Request $request)
    {
        parent::__construct($request);

        $projectID = $request->uuid;

        $project = app()->make('ProjectManager')->getProject($projectID);

        $tasks = app()->make('GetTasksInteractor')->execute(new GetTasksRequest([
            'userID' => $this->getUser()->id,
            'projectID' => $projectID,
        ]));
        $tasksSpentTime = app()->make('GetTasksTotalTimeInteractor')->getTasksTotalSpentTime($this->getUser()->id, $projectID);
        $tasksScheduledTime = new \StdClass();
        $tasksScheduledTime->days = $project->tasksScheduledTime;
        $tasksScheduledTime->hours = 0;
        $tasksRemainingTime = app()->make('GetRemainingTimeinteractor')->getRemainingTime($tasksScheduledTime, $tasksSpentTime);

        $tickets = app()->make('GetTicketInteractor')->getTicketsList(
            $this->getUser()->id,
            $projectID,
            null,
            null
        );
        $ticketsSpentTime = app()->make('GetTicketsTotalTimeInteractor')->getTicketsTotalSpentTime($this->getUser()->id, $projectID);
        $ticketsScheduledTime = new \StdClass();
        $ticketsScheduledTime->days = $project->ticketsScheduledTime;
        $ticketsScheduledTime->hours = 0;
        $ticketsRemainingTime = app()->make('GetRemainingTimeinteractor')->getRemainingTime($ticketsScheduledTime, $ticketsSpentTime);

        $ticketStatusesColors = ['#d9534f', '#EC970D', '#29595E', '#5cb85c', '#8DA899'];
        $ticketStatuses = app()->make('TicketStatusManager')->getTicketStatuses();
        foreach ($ticketStatuses as $i => $ticketStatus) {
            $ticketStatuses[$i]->count = sizeof(app()->make('GetTicketInteractor')->getTicketsList($this->getUser()->id, $projectID, null, $ticketStatus->id));
            $ticketStatuses[$i]->color = isset($ticketStatusesColors[$i]) ? $ticketStatusesColors[$i] : $ticketStatusesColors[0];
        }

        return view('projectsquare::project.reporting', [
            'users' => app()->make('UserManager')->getAgencyUsers(),
            'project' => $project,
            'tasks' => $tasks,
            'task_statuses' => TaskController::getTasksStatuses(),
            'filters' => [
                'allocated_user' => Input::get('filter_allocated_user'),
                'status' => Input::get('filter_status'),
            ],
            'todo_tasks_count' => app()->make('GetReportingIndicatorsInteractor')->getTasksCountByStatus($this->getUser()->id, $projectID, Task::TODO),
            'in_progress_tasks_count' => app()->make('GetReportingIndicatorsInteractor')->getTasksCountByStatus($this->getUser()->id, $projectID, Task::IN_PROGRESS),
            'completed_tasks_count' => app()->make('GetReportingIndicatorsInteractor')->getTasksCountByStatus($this->getUser()->id, $projectID, Task::COMPLETED),
            'total_tasks_spent_time_days' => $tasksSpentTime->days,
            'total_tasks_spent_time_hours' => $tasksSpentTime->hours,
            'total_tasks_remaining_time_days' => $tasksRemainingTime->days,
            'total_tasks_remaining_time_hours' => $tasksRemainingTime->hours,
            'tasks_progress_percentage' => app()->make('GetReportingIndicatorsInteractor')->getProgressPercentage($this->getUser()->id, $projectID, $project->tasksScheduledTime),
            'tasks_profitability_percentage' => app()->make('GetReportingIndicatorsInteractor')->getProfitabilityPercentage($project->tasksScheduledTime, $tasksSpentTime),
            'tickets_profitability_percentage' => app()->make('GetReportingIndicatorsInteractor')->getProfitabilityPercentage($project->ticketsScheduledTime, $ticketsSpentTime),
            'tickets' => $tickets,
            'total_tickets_spent_time_days' => $ticketsSpentTime->days,
            'total_tickets_spent_time_hours' => $ticketsSpentTime->hours,
            'total_tickets_remaining_time_days' => $ticketsRemainingTime->days,
            'total_tickets_remaining_time_hours' => $ticketsRemainingTime->hours,
            'ticket_statuses' => $ticketStatuses,
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
}
