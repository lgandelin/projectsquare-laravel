<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Webaccess\ProjectSquare\Requests\Tasks\CreateTaskRequest;
use Webaccess\ProjectSquare\Requests\Tasks\DeleteTaskRequest;
use Webaccess\ProjectSquare\Requests\Tasks\GetTasksRequest;
use Webaccess\ProjectSquare\Requests\Tasks\UpdateTaskRequest;
use Webaccess\ProjectSquareLaravel\Models\Task;
use Illuminate\Support\Facades\Input;

class TaskController extends BaseController
{
    public function index()
    {
        return view('projectsquare::todo.index', [
            'tasks' => app()->make('GetTasksInteractor')->execute(new GetTasksRequest([
                'userID' => $this->getUser()->id]
            ))
        ]);
    }

    public function store()
    {
        $response = app()->make('CreateTaskInteractor')->execute(new CreateTaskRequest([
            'name' => Input::get('name'),
            'userID' => Input::get('user_id') ? Input::get('user_id') : $this->getUser()->id,
            'status' => false,
        ]));

        return redirect()->route('to_do_index');
    }

    public function update()
    {
        $task = Task::find(Input::get('id'));
        $response = app()->make('UpdateTaskInteractor')->execute(new UpdateTaskRequest([
            'taskID' => Input::get('id'),
            'name' => Input::get('name'),
            'status' => !$task->status,
            'requesterUserID' => $this->getUser()->id,
        ]));

        return redirect()->route('to_do_index');
    }

    public function delete($taskID)
    {
        app()->make('DeleteTaskInteractor')->execute(new DeleteTaskRequest([
            'taskID' => $taskID,
            'requesterUserID' => $this->getUser()->id,
        ]));

        return redirect()->route('to_do_index');
    }
}
