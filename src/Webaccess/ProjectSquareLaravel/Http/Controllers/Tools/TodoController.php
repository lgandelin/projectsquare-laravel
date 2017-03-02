<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Tools;

use Webaccess\ProjectSquare\Requests\Todos\CreateTodoRequest;
use Webaccess\ProjectSquare\Requests\Todos\DeleteTodoRequest;
use Webaccess\ProjectSquare\Requests\Todos\UpdateTodoRequest;
use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;

class TodoController extends BaseController
{

    public function create()
    {
        try {
            $response = app()->make('CreateTodoInteractor')->execute(new CreateTodoRequest([
                'name' => Input::get('name'),
                'userID' => Input::get('user_id') ? Input::get('user_id') : $this->getUser()->id,
                'status' => false,
            ]));

            return response()->json([
                'todo' => $response->todo,
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
            $response = app()->make('UpdateTodoInteractor')->execute(new UpdateTodoRequest([
                'todoID' => Input::get('todo_id'),
                'status' => Input::get('status'),
                'requesterUserID' => $this->getUser()->id,
            ]));

            return response()->json([
                'message' => trans('projectsquare::todos.edit_todo_success'),
                'todo' => $response->todo,
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
            $response = app()->make('DeleteTodoInteractor')->execute(new DeleteTodoRequest([
                'todoID' => Input::get('todo_id'),
                'requesterUserID' => $this->getUser()->id,
            ]));

            return response()->json([
                'message' => trans('projectsquare::todos.delete_todo_success'),
                'todo' => $response->todo,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
