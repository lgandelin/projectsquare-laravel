<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Tools;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Webaccess\ProjectSquare\Requests\Notifications\ReadNotificationRequest;
use Webaccess\ProjectSquare\Requests\Phases\GetPhasesRequest;
use Webaccess\ProjectSquare\Requests\Planning\GetEventsRequest;
use Webaccess\ProjectSquare\Requests\Tasks\GetTasksRequest;
use Webaccess\ProjectSquare\Requests\Tasks\GetTaskRequest;
use Webaccess\ProjectSquare\Requests\Tasks\CreateTaskRequest;
use Webaccess\ProjectSquare\Requests\Tasks\UnallocateTaskRequest;
use Webaccess\ProjectSquare\Requests\Tasks\UpdateTaskRequest;
use Webaccess\ProjectSquare\Requests\Tasks\DeleteTaskRequest;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;
use Webaccess\ProjectSquareLaravel\Http\Controllers\Management\OccupationController;
use Webaccess\ProjectSquareLaravel\Tools\StringTool;

class TaskController extends BaseController
{
    public function index(Request $request)
    {
        parent::__construct($request);

        $itemsPerPage = $request->get('it') ? $request->get('it') : env('TASKS_PER_PAGE', 10);

        $request->session()->put('tasks_interface', 'tasks');

        if (Input::get('filter_project') !== null) Session::put('tasks_filter_project', Input::get('filter_project'));
        if (Input::get('filter_status') !== null) Session::put('tasks_filter_status', Input::get('filter_status'));
        if (Input::get('filter_allocated_user') !== null) Session::put('tasks_filter_allocated_user', Input::get('filter_allocated_user'));

        $tasks = app()->make('GetTasksInteractor')->getTasksPaginatedList($this->getUser()->id, $itemsPerPage, $request->get('sc'), $request->get('so'), new GetTasksRequest([
            'projectID' => Session::get('tasks_filter_project') === "na" ? null : Session::get('tasks_filter_project'),
            'statusID' => Session::get('tasks_filter_status') === "na" ? null : Session::get('tasks_filter_status'),
            'allocatedUserID' => Session::get('tasks_filter_allocated_user') === "na" ? null : Session::get('tasks_filter_allocated_user'),
            'phaseID' => false,
        ]));

        return view('projectsquare::tools.tasks.index', [
            'tasks' => $tasks,
            'projects' => app()->make('GetProjectsInteractor')->getCurrentProjects($this->getUser()->id),
            'users' => app()->make('UserManager')->getAgencyUsers(),
            'task_statuses' => self::getTasksStatuses(),
            'filters' => [
                'project' => Session::get('tasks_filter_project'),
                'allocated_user' => Session::get('tasks_filter_allocated_user'),
                'status' => Session::get('tasks_filter_status'),
            ],
            'items_per_page' => $request->get('it') ? $request->get('it') : $itemsPerPage,
            'sort_column' => $request->get('sc'),
            'sort_order' => ($request->get('so') == 'asc') ? 'desc' : 'asc',
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function add(Request $request)
    {
        parent::__construct($request);

        return view('projectsquare::tools.tasks.add', [
            'projects' => app()->make('GetProjectsInteractor')->getProjects($this->getUser()->id),
            'phases' => app()->make('GetPhasesInteractor')->execute(new GetPhasesRequest([
                'projectID' => $this->getCurrentProject()->id,
            ])),
            'users' => ($this->getCurrentProject()) ? app()->make('UserManager')->getUsersByProject($this->getCurrentProject()->id) : app()->make('UserManager')->getAgencyUsers(),
            'current_project_id' => ($this->getCurrentProject()) ? $this->getCurrentProject()->id : null,
            'task_statuses' => self::getTasksStatuses(),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'data' => ($request->session()->has('data')) ? $request->session()->get('data') : null,
            'back_link' => ($request->session()->get('tasks_interface') === 'project') ? route('project_tasks', ['uuid' => $this->getCurrentProject()->id]) : route('tasks_index')
        ]);
    }

    public function store(Request $request)
    {
        parent::__construct($request);

        try {
            $data = [
                'title' => Input::get('title'),
                'description' => Input::get('description'),
                'phaseID' => Input::get('phase_id'),
                'projectID' => Input::get('project_id'),
                'statusID' => Input::get('status_id'),
                'estimatedTimeDays' => StringTool::formatNumber(Input::get('estimated_time_days')),
                'estimatedTimeHours' => StringTool::formatNumber(Input::get('estimated_time_hours')),
                'spentTimeDays' => 0,
                'spentTimeHours' => 0,
                'allocatedUserID' => Input::get('allocated_user_id'),
                'requesterUserID' => $this->getUser()->id,
            ];
            $request->session()->flash('data', $data);
            $response = app()->make('CreateTaskInteractor')->execute(new CreateTaskRequest($data));

            $request->session()->flash('confirmation', trans('projectsquare::tasks.add_task_success'));

            return ($request->session()->get('tasks_interface') === 'project') ? redirect()->route('project_tasks', ['uuid' => $this->getCurrentProject()->id]) : redirect()->route('tasks_index');
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
        }

        return redirect()->route('tasks_add');
    }

    public function edit(Request $request)
    {
        parent::__construct($request);

        $taskID = $request->uuid;

        //Read linked notification
        $notifications = $this->getUnreadNotifications();
        if (is_array($notifications) && sizeof($notifications) > 0) {
            foreach ($notifications as $notification) {
                if ($notification->entityID == $taskID) {
                    app()->make('ReadNotificationInteractor')->execute(new ReadNotificationRequest([
                        'notificationID' => $notification->id,
                        'userID' => $this->getUser()->id,
                    ]));
                }
            }
        }

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


        return view('projectsquare::tools.tasks.edit', [
            'task' => $task,
            'projects' => app()->make('GetProjectsInteractor')->getCurrentProjects($this->getUser()->id),
            'task_statuses' => self::getTasksStatuses(),
            'users' => app()->make('UserManager')->getUsersByProject($task->projectID),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
            'back_link' => ($request->session()->get('tasks_interface') === 'project') ? route('project_tasks', ['uuid' => $this->getCurrentProject()->id]) : route('tasks_index')
        ]);
    }

    public function update(Request $request)
    {
        parent::__construct($request);

        try {
            app()->make('UpdateTaskInteractor')->execute(new UpdateTaskRequest([
                'taskID' => Input::get('task_id'),
                'title' => Input::get('title'),
                'projectID' => Input::get('project_id'),
                'statusID' => Input::get('status_id'),
                'allocatedUserID' => Input::get('allocated_user_id'),
                'description' => Input::get('description'),
                'estimatedTimeDays' => StringTool::formatNumber(Input::get('estimated_time_days')),
                'estimatedTimeHours' => StringTool::formatNumber(Input::get('estimated_time_hours')),
                'spentTimeDays' => StringTool::formatNumber(Input::get('spent_time_days')),
                'spentTimeHours' => StringTool::formatNumber(Input::get('spent_time_hours')),
                'requesterUserID' => $this->getUser()->id,
            ]));

            $request->session()->flash('confirmation', trans('projectsquare::tasks.edit_task_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
        }

        if ($request->session()->get('tasks_interface') === 'project')
            return redirect()->route('project_tasks_edit', ['uuid' => $this->getCurrentProject()->id, 'task_uuid' => Input::get('task_id')]);

        return redirect()->route('tasks_edit', ['id' => Input::get('task_id')]);
    }

    public function delete(Request $request)
    {
        parent::__construct($request);

        $taskID = $request->uuid;

        try {
            app()->make('DeleteTaskInteractor')->execute(new DeleteTaskRequest([
                'taskID' => $taskID,
                'requesterUserID' => $this->getUser()->id,
            ]));
            $request->session()->flash('confirmation', trans('projectsquare::tasks.delete_task_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
        }

        return redirect()->route('tasks_index');
    }

    public static function getTasksStatuses()
    {
        $tasksStatus1 = new \StdClass();
        $tasksStatus1->id = 1;
        $tasksStatus1->name = 'A faire';

        $tasksStatus2 = new \StdClass();
        $tasksStatus2->id = 2;
        $tasksStatus2->name = 'En cours';

        $tasksStatus3 = new \StdClass();
        $tasksStatus3->id = 3;
        $tasksStatus3->name = 'Terminé';

        return [
            $tasksStatus1,
            $tasksStatus2,
            $tasksStatus3,
        ];
    }
    
    public function unallocate(Request $request)
    {
        parent::__construct($request);

        try {
            app()->make('UnallocateTaskInteractor')->execute(new UnallocateTaskRequest([
                'taskID' => Input::get('task_id'),
                'requesterUserID' => $this->getUser()->id,
                'allocatedUserID' => null
            ]));

            $calendars = view('projectsquare::management.occupation.includes.calendar', [
                'month_labels' => OccupationController::getMonthLabels(),
                'calendars' => OccupationController::getUsersCalendarsByRole(Input::get('filter_role')),
            ])->render();

            return response()->json([
                'success' => true,
                'calendars' => $calendars
            ], 200);
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}