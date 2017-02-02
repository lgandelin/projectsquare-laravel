<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Utility;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Requests\Notifications\ReadNotificationRequest;
use Webaccess\ProjectSquare\Requests\Tasks\GetTasksRequest;
use Webaccess\ProjectSquare\Requests\Tasks\GetTaskRequest;
use Webaccess\ProjectSquare\Requests\Tasks\CreateTaskRequest;
use Webaccess\ProjectSquare\Requests\Tasks\UpdateTaskRequest;
use Webaccess\ProjectSquare\Requests\Tasks\DeleteTaskRequest;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;
use Webaccess\ProjectSquareLaravel\Models\User;
use Webaccess\ProjectSquareLaravel\Tools\StringTool;

class TaskController extends BaseController
{
    public function index(Request $request)
    {
        parent::__construct($request);

        $request->session()->put('tasks_interface', 'tasks');

        return view('projectsquare::tasks.index', [
            'tasks' => app()->make('GetTasksInteractor')->getTasksPaginatedList($this->getUser()->id, env('TASKS_PER_PAGE', 10), new GetTasksRequest([
                'projectID' => Input::get('filter_project'),
                'statusID' => Input::get('filter_status'),
                'allocatedUserID' => Input::get('filter_allocated_user'),
            ])),
            'projects' => app()->make('GetProjectsInteractor')->getProjects($this->getUser()->id),
            'users' => app()->make('UserManager')->getAgencyUsers(),
            'task_statuses' => self::getTasksStatuses(),
            'filters' => [
                'project' => Input::get('filter_project'),
                'allocated_user' => Input::get('filter_allocated_user'),
                'status' => Input::get('filter_status'),
            ],
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    protected function getUserWithProjects()
    {
        if ($user = Auth::user())
            return User::with('projects.client')->find($user->id);

        return null;
    }

    public function add(Request $request)
    {
        parent::__construct($request);

        return view('projectsquare::tasks.add', [
            'projects' => app()->make('GetProjectsInteractor')->getProjects($this->getUser()->id),
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

            return redirect()->route('tasks_index');
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

        return view('projectsquare::tasks.edit', [
            'task' => $task,
            'projects' => app()->make('GetProjectsInteractor')->getProjects($this->getUser()->id),
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
            app()->make('UpdateTaskInteractor')->execute(new UpdateTaskRequest([
                'taskID' => Input::get('task_id'),
                'requesterUserID' => $this->getUser()->id,
                'allocatedUserID' => 0
            ]));

            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}