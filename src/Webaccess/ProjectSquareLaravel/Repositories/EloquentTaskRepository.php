<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquare\Entities\Task as TaskEntity;
use Webaccess\ProjectSquare\Repositories\TaskRepository;
use Webaccess\ProjectSquareLaravel\Models\Task;

class EloquentTaskRepository implements TaskRepository
{
    public function getTask($taskID)
    {
        $taskModel = $this->getTaskModel($taskID);

        return $this->getTaskEntity($taskModel);
    }

    public function getTaskModel($taskID)
    {
        return Task::find($taskID);
    }

    public function getTasks($userID)
    {
        $tasks = [];
        $tasksModel = Task::where('user_id', '=', $userID);
        foreach ($tasksModel->get() as $taskModel) {
            $task = $this->getTaskEntity($taskModel);
            $tasks[] = $task;
        }

        return $tasks;
    }

    public function persistTask(TaskEntity $task)
    {
        $taskModel = (!isset($task->id)) ? new Task() : Task::find($task->id);
        $taskModel->name = $task->name;
        $taskModel->user_id = $task->userID;
        $taskModel->status = $task->status;

        $taskModel->save();

        $task->id = $taskModel->id;

        return $task;
    }

    public function removeTask($taskID)
    {
        $task = $this->getTaskModel($taskID);
        $task->delete();
    }

    private function getTaskEntity($taskModel)
    {
        $task = new TaskEntity();
        $task->id = $taskModel->id;
        $task->name = $taskModel->name;
        $task->userID = $taskModel->user_id;
        $task->status = $taskModel->status;

        return $task;
    }
}
