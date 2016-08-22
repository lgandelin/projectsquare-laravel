<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquare\Entities\Todo as TodoEntity;
use Webaccess\ProjectSquare\Repositories\TodoRepository;
use Webaccess\ProjectSquareLaravel\Models\Todo;

class EloquentTodoRepository implements TodoRepository
{
    public function getTodo($todoID)
    {
        $todoModel = $this->getTodoModel($todoID);

        return $this->getTodoEntity($todoModel);
    }

    public function getTodoModel($todoID)
    {
        return Todo::find($todoID);
    }

    public function getTodos($userID)
    {
        $todos = [];
        $todosModel = Todo::where('user_id', '=', $userID);
        foreach ($todosModel->get() as $todoModel) {
            $todo = $this->getTodoEntity($todoModel);
            $todos[] = $todo;
        }

        return $todos;
    }

    public function persistTodo(TodoEntity $todo)
    {
        $todoModel = (!isset($todo->id)) ? new Todo() : Todo::find($todo->id);
        $todoModel->name = $todo->name;
        $todoModel->user_id = $todo->userID;
        $todoModel->status = $todo->status;

        $todoModel->save();

        $todo->id = $todoModel->id;

        return $todo;
    }

    public function removeTodo($todoID)
    {
        $todo = $this->getTodoModel($todoID);
        $todo->delete();
    }

    private function getTodoEntity($todoModel)
    {
        $todo = new TodoEntity();
        $todo->id = $todoModel->id;
        $todo->name = $todoModel->name;
        $todo->userID = $todoModel->user_id;
        $todo->status = $todoModel->status;

        return $todo;
    }
}
