<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Utility;

use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Requests\Tasks\GetTasksRequest;
use Webaccess\ProjectSquare\Requests\Tasks\GetTaskRequest;
use Webaccess\ProjectSquare\Requests\Tasks\CreateTaskRequest;
use Webaccess\ProjectSquare\Requests\Tasks\UpdateTaskRequest;
use Webaccess\ProjectSquare\Requests\Tasks\DeleteTaskRequest;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;

class TaskController extends BaseController
{
    public function index()
    {
        return view('projectsquare::tasks.index', [
            'tasks' => app()->make('GetTasksInteractor')->getTasksPaginatedList(env('TASKS_PER_PAGE', 10), new GetTasksRequest([
                'projectID' => Input::get('filter_project'),
                'statusID' => Input::get('filter_status'),
                'allocatedUserID' => Input::get('filter_allocated_user'),
            ])),
            'projects' => app()->make('ProjectManager')->getUserProjects($this->getUser()->id),
            'users' => app()->make('UserManager')->getUsers(),
            'task_statuses' => self::getTasksStatuses(),
            'filters' => [
                'project' => Input::get('filter_project'),
                'allocated_user' => Input::get('filter_allocated_user'),
                'status' => Input::get('filter_status'),
            ],
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function add()
    {
        return view('projectsquare::tasks.add', [
            'projects' => app()->make('ProjectManager')->getProjects(),
            'users' => app()->make('UserManager')->getAgencyUsers(),
            'current_project_id' => ($this->getCurrentProject()) ? $this->getCurrentProject()->id : null,
            'task_statuses' => self::getTasksStatuses(),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'data' => ($this->request->session()->has('data')) ? $this->request->session()->get('data') : null
        ]);
    }

    public function store()
    {
        try {
            $data = [
                'title' => Input::get('title'),
                'description' => Input::get('description'),
                'projectID' => Input::get('project_id'),
                'statusID' => Input::get('status_id'),
                'estimatedTimeDays' => Input::get('estimated_time_days'),
                'estimatedTimeHours' => Input::get('estimated_time_hours'),
                'spentTimeDays' => Input::get('spent_time_days'),
                'spentTimeHours' => Input::get('spent_time_hours'),
                'allocatedUserID' => Input::get('allocated_user_id'),
                'requesterUserID' => $this->getUser()->id,
            ];
            $this->request->session()->flash('data', $data);
            $response = app()->make('CreateTaskInteractor')->execute(new CreateTaskRequest($data));

            $this->request->session()->flash('confirmation', trans('projectsquare::tasks.add_task_success'));

            return redirect()->route('tasks_edit', ['id' => $response->task->id]);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', $e->getMessage());
        }

        return redirect()->route('tasks_add');
    }

    public function edit($taskID)
    {
        try {
            $task = app()->make('GetTaskInteractor')->execute(new GetTaskRequest([
                'taskID' => $taskID,
                'requesterUserID' => $this->getUser()->id,
            ]));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', $e->getMessage());

            return redirect()->route('tasks_index');
        }

        return view('projectsquare::tasks.edit', [
            'task' => $task,
            'projects' => app()->make('ProjectManager')->getProjects(),
            'task_statuses' => self::getTasksStatuses(),
            'users' => app()->make('UserManager')->getAgencyUsers(),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function update()
    {
        try {
            app()->make('UpdateTaskInteractor')->execute(new UpdateTaskRequest([
                'taskID' => Input::get('task_id'),
                'title' => Input::get('title'),
                'projectID' => Input::get('project_id'),
                'statusID' => Input::get('status_id'),
                'allocatedUserID' => Input::get('allocated_user_id'),
                'description' => Input::get('description'),
                'estimatedTimeDays' => Input::get('estimated_time_days'),
                'estimatedTimeHours' => Input::get('estimated_time_hours'),
                'spentTimeDays' => Input::get('spent_time_days'),
                'spentTimeHours' => Input::get('spent_time_hours'),
                'requesterUserID' => $this->getUser()->id,
            ]));

            $this->request->session()->flash('confirmation', trans('projectsquare::tasks.edit_task_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', $e->getMessage());
        }

        return redirect()->route('tasks_edit', ['id' => Input::get('task_id')]);
    }

    public function delete($taskID)
    {
        try {
            app()->make('DeleteTaskInteractor')->execute(new DeleteTaskRequest([
                'taskID' => $taskID,
                'requesterUserID' => $this->getUser()->id,
            ]));
            $this->request->session()->flash('confirmation', trans('projectsquare::tasks.delete_task_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', $e->getMessage());
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
    
    public function unallocate()
    {
        try {
            app()->make('UpdateTaskInteractor')->execute(new UpdateTaskRequest([
                'taskID' => Input::get('task_id'),
                'requesterUserID' => $this->getUser()->id,
                'allocatedUserID' => 0
            ]));

            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}