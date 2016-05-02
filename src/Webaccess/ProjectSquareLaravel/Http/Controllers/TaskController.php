<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Webaccess\ProjectSquare\Requests\Tasks\CreateTaskRequest;
use Webaccess\ProjectSquare\Requests\Tasks\DeleteTaskRequest;
use Webaccess\ProjectSquare\Requests\Tasks\GetTasksRequest;
use Webaccess\ProjectSquare\Requests\Tasks\UpdateTaskRequest;
use Illuminate\Support\Facades\Input;

class TaskController extends BaseController
{
    public function index()
    {
        return view('projectsquare::tasks.index', [
            'tasks' => app()->make('GetTasksInteractor')->execute(new GetTasksRequest([
                'userID' => $this->getUser()->id, ]
            )),
        ]);
    }

    public function create()
    {
        try {
            $response = app()->make('CreateTaskInteractor')->execute(new CreateTaskRequest([
                'name' => Input::get('name'),
                'userID' => Input::get('user_id') ? Input::get('user_id') : $this->getUser()->id,
                'status' => false,
            ]));

            return response()->json([
                'task' => $response->task,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update()
    {
        try {
            $response = app()->make('UpdateTaskInteractor')->execute(new UpdateTaskRequest([
                'taskID' => Input::get('task_id'),
                'status' => Input::get('status'),
                'requesterUserID' => $this->getUser()->id,
            ]));

            return response()->json([
                'message' => trans('projectsquare::tasks.edit_task_success'),
                'task' => $response->task,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete()
    {
        try {
            $response = app()->make('DeleteTaskInteractor')->execute(new DeleteTaskRequest([
                'taskID' => Input::get('task_id'),
                'requesterUserID' => $this->getUser()->id,
            ]));

            return response()->json([
                'message' => trans('projectsquare::tasks.delete_task_success'),
                'task' => $response->task,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
