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

    public function getTasks($projectID = null, $statusID = null, $allocatedUserID = null, $entities = false)
    {
        $tasks = $this->getTasksList($projectID, $statusID, $allocatedUserID, $entities);

        return $entities ? $tasks : $tasks->get();
    }

    public function getTasksList($projectID = null, $statusID = null, $allocatedUserID = null, $entities = false)
    {
        if ($projectID) {
            $tasks = Task::with('project', 'project.client')->with('project.client')->where('project_id', '=', $projectID);
        } else {
            $tasks = Task::with('project', 'project.client')->with('project.client');
        }

        if ($statusID !== null) {
            $tasks->where('status_id', '=', $statusID);
        }

        if ($allocatedUserID !== null) {
            $tasks->where('allocated_user_id', '=', $allocatedUserID);
        }

        $tasks->orderBy('updated_at', 'DESC');

        if ($entities) {
            $tasksList = $tasks->get();

            $result = [];
            foreach ($tasksList as $taskModel) {
                $result[]= $this->getTaskEntity($taskModel);
            }

            return $result;
        }

        return $tasks;
    }

    public function getTasksPaginatedList($limit, $projectID = null, $statusID = null, $allocatedUserID = null)
    {
        return $this->getTasksList($projectID, $statusID, $allocatedUserID)->paginate($limit);
    }

    public function persistTask(TaskEntity $todo)
    {
        $todoModel = (!isset($todo->id)) ? new Task() : Task::find($todo->id);
        $todoModel->title = $todo->title;
        $todoModel->description = $todo->description;
        $todoModel->estimated_time_days = $todo->estimatedTimeDays;
        $todoModel->estimated_time_hours = $todo->estimatedTimeHours;
        $todoModel->spent_time_days = $todo->spentTimeDays;
        $todoModel->spent_time_hours = $todo->spentTimeHours;
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
        $todo->estimatedTimeDays = $todoModel->estimated_time_days;
        $todo->estimatedTimeHours = $todoModel->estimated_time_hours;
        $todo->spentTimeDays = $todoModel->spent_time_days;
        $todo->spentTimeHours = $todoModel->spent_time_hours;
        $todo->projectID = $todoModel->project_id;
        $todo->statusID = $todoModel->status_id;
        $todo->allocatedUserID = $todoModel->allocated_user_id;

        return $todo;
    }
}