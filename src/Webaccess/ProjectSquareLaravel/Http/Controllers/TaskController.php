<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Webaccess\ProjectSquareLaravel\Models\Task;
use Illuminate\Support\Facades\Input;

class TaskController extends BaseController
{
    public function index()
    {
        return view('projectsquare::todo.index', [
            'tasks' => Task::where('user_id', '=', $this->getUser()->id)->get(),
        ]);
    }

    public function store()
    {
        $task = new Task();
        $task->name = Input::get('name');
        $task->status = 0;
        $task->user_id = $this->getUser()->id;
        $task->save();

        return redirect()->route('to_do_index');
    }

    public function update()
    {
        $task = Task::find(Input::get('id'));
        $task->status = !$task->status;
        $task->save();

        return redirect()->route('to_do_index');
    }

    public function delete($userID)
    {
        $task = Task::find($userID);

        $task->delete();

        return redirect()->route('to_do_index');
    }
}
