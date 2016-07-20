<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Utility;

use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;
use Webaccess\ProjectSquare\Requests\Tasks\GetTasksRequest;

class TaskController extends BaseController
{
    public function index()
    {
        return view('projectsquare::tasks.index', [
            'tasks' => app()->make('GetTasksInteractor')->execute(new GetTasksRequest()),
            'projects' => app()->make('ProjectManager')->getUserProjects($this->getUser()->id),
            'users' => app()->make('UserManager')->getUsers(),
            'task_statuses' => [],
            'filters' => [
                'project' => Input::get('filter_project'),
                'allocated_user' => Input::get('filter_allocated_user'),
                'status' => Input::get('filter_status'),
            ],
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }
}