<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Entities\Task;
use Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\TaskController;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentTicketRepository;
use Webaccess\ProjectSquare\Requests\Tasks\GetTasksRequest;

class ProjectController extends BaseController
{
    public function index($projectID)
    {
        return view('projectsquare::project.cms', [
            'project' => app()->make('ProjectManager')->getProject($projectID),
        ]);
    }

    public function tasks($projectID)
    {
        return view('projectsquare::project.tasks', [
            'project' => app()->make('ProjectManager')->getProject($projectID),
            'projects' => app()->make('ProjectManager')->getProjects(),
            'users' => app()->make('UserManager')->getUsers(),
            'task_statuses' => TaskController::getTasksStatuses(),
            'filters' => [
                'allocated_user' => Input::get('filter_allocated_user'),
                'status' => Input::get('filter_status'),
                'type' => Input::get('filter_type'),
            ],
            'tasks' => app()->make('GetTasksInteractor')->execute(new GetTasksRequest([
                'projectID' => $projectID,
                'statusID' => Input::get('filter_status'),
                'allocatedUserID' => Input::get('filter_allocated_user'),
            ])),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function tickets($projectID)
    {
        return view('projectsquare::project.tickets', [
            'project' => app()->make('ProjectManager')->getProject($projectID),
            'projects' => app()->make('ProjectManager')->getProjects(),
            'users' => app()->make('UserManager')->getUsers(),
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
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function monitoring($projectID)
    {
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

    public function messages($projectID)
    {
        return view('projectsquare::project.messages', [
            'project' => app()->make('ProjectManager')->getProject($projectID),
            'conversations' => app()->make('ConversationManager')->getConversationsByProject($projectID),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function seo($projectID)
    {
        $gaViewID = app()->make('SettingManager')->getSettingByKeyAndProject('GA_VIEW_ID', $projectID);

        return view('projectsquare::project.seo', [
            'project' => app()->make('ProjectManager')->getProject($projectID),
            'gaViewID' => ($gaViewID) ? $gaViewID->value : null,
            'startDate' => (new Carbon())->addDay(-30)->format('d/m/Y'),
            'endDate' => (new Carbon())->format('d/m/Y'),
        ]);
    }

    public function progress($projectID)
    {
        $project = app()->make('ProjectManager')->getProject($projectID);
        $tasks = app()->make('GetTasksInteractor')->execute(new GetTasksRequest([
            'projectID' => $projectID,
            'statusID' => Input::get('filter_status'),
            'allocatedUserID' => Input::get('filter_allocated_user'),
        ]));

        $estimatedTime = app()->make('GetTasksTotalTimeInteractor')->getTasksTotalEstimatedTime($projectID);
        $spentTime = app()->make('GetTasksTotalTimeInteractor')->getTasksTotalSpentTime($projectID);

        return view('projectsquare::project.progress', [
            'users' => app()->make('UserManager')->getAgencyUsers(),
            'project' => $project,
            'tasks' => $tasks,
            'task_statuses' => TaskController::getTasksStatuses(),
            'filters' => [
                'allocated_user' => Input::get('filter_allocated_user'),
                'status' => Input::get('filter_status'),
            ],
            'todo_tasks_count' => app()->make('GetProgressIndicatorsInteractor')->getTasksCountByStatus($projectID, Task::TODO),
            'in_progress_tasks_count' => app()->make('GetProgressIndicatorsInteractor')->getTasksCountByStatus($projectID, Task::IN_PROGRESS),
            'completed_tasks_count' => app()->make('GetProgressIndicatorsInteractor')->getTasksCountByStatus($projectID, Task::COMPLETED),
            'total_estimated_time_days' => $estimatedTime->days,
            'total_estimated_time_hours' => $estimatedTime->hours,
            'total_spent_time_days' => $spentTime->days,
            'total_spent_time_hours' => $spentTime->hours,
            'progress_percentage' => app()->make('GetProgressIndicatorsInteractor')->getProgressPercentage($projectID, $tasks),
            'profitability_percentage' => app()->make('GetProgressIndicatorsInteractor')->getProfitabilityPercentage($project->scheduledTime, $spentTime)
        ]);
    }
}
