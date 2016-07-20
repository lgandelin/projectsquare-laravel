<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquare\Repositories\TaskRepository;
use Webaccess\ProjectSquare\Entities\Task as TaskEntity;
use Webaccess\ProjectSquareLaravel\Models\Task;

class EloquentTasksRepository implements TaskRepository
{
    public function getTask($todoID)
    {
        $todoModel = $this->getTaskModel($todoID);

        return $this->getTaskEntity($todoModel);
    }

    public function getTaskModel($todoID)
    {
        return Task::find($todoID);
    }

    public function getTasks($projectID = null)
    {
        $todos = [];
        $todosModel = Task::where('project_id', '=', $projectID);
        foreach ($todosModel->get() as $todoModel) {
            $todo = $this->getTaskEntity($todoModel);
            $todos[] = $todo;
        }

        return $todos;
    }

    public function persistTask(TaskEntity $todo)
    {
        $todoModel = (!isset($todo->id)) ? new Task() : Task::find($todo->id);
        $todoModel->title = $todo->title;
        $todoModel->project_id = $todo->projectID;
        $todoModel->status = $todo->status;

        $todoModel->save();

        $todo->id = $todoModel->id;

        return $todo;
    }

    public function deleteTask($todoID)
    {
        $todo = $this->getTaskModel($todoID);
        $todo->delete();
    }

    private function getTaskEntity($todoModel)
    {
        $todo = new TaskEntity();
        $todo->id = $todoModel->id;
        $todo->title = $todoModel->title;
        $todo->projectID = $todoModel->project_id;
        $todo->status = $todoModel->status;

        return $todo;
    }
}