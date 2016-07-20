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

    public function getTasks($projectID = null, $statusID = null, $allocatedUserID = null)
    {
        if ($projectID) {
            $todos = Task::with('project', 'project.client')->with('project.client')->where('project_id', '=', $projectID);
        } else {
            $todos = Task::with('project', 'project.client')->with('project.client');
        }

        if ($statusID) {
            $todos->where('status_id', '=', $statusID);
        }

        if ($allocatedUserID) {
            $todos->where('allocated_user_id', '=', $allocatedUserID);
        }

        return $todos->get();
    }

    public function persistTask(TaskEntity $todo)
    {
        $todoModel = (!isset($todo->id)) ? new Task() : Task::find($todo->id);
        $todoModel->title = $todo->title;
        $todoModel->description = $todo->description;
        $todoModel->estimated_time = $todo->estimatedTime;
        $todoModel->project_id = $todo->projectID;
        $todoModel->status_id = $todo->statusID;
        $todoModel->allocated_user_id = $todo->allocatedUserID;

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
        $todo->description = $todoModel->description;
        $todo->estimatedTime = $todoModel->estimated_time;
        $todo->projectID = $todoModel->project_id;
        $todo->statusID = $todoModel->status_id;
        $todo->allocatedUserID = $todoModel->allocated_user_id;

        return $todo;
    }
}