<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Ramsey\Uuid\Uuid;
use Webaccess\ProjectSquare\Repositories\TaskRepository;
use Webaccess\ProjectSquare\Entities\Task as TaskEntity;
use Webaccess\ProjectSquareLaravel\Models\Project;
use Webaccess\ProjectSquareLaravel\Models\Task;
use Webaccess\ProjectSquareLaravel\Models\User;

class EloquentTasksRepository implements TaskRepository
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

    public function getTasks($userID, $projectID = null, $statusID = null, $allocatedUserID = null, $entities = false)
    {
        $tasks = $this->getTasksList($userID, $projectID, $statusID, $allocatedUserID, $entities);

        return $entities ? $tasks : $tasks->get();
    }

    public function getTasksList($userID, $projectID = null, $statusID = null, $allocatedUserID = null, $entities = false)
    {
        $projectIDs = [];

        //Ressource projects
        $user = User::find($userID);
        if ($user->projects) {
            $projectIDs = $user->projects->pluck('id')->toArray();
        }

        //Client project
        if (isset($user->client_id)) {
            $project = Project::where('client_id', '=', $user->client_id)->first();
            $projectIDs[]= $project->id;
        }

        $tasks = Task::whereIn('project_id', $projectIDs)->with('project', 'project.client')->with('project.client');

        if ($projectID) {
            $tasks->where('project_id', '=', $projectID);
        }

        if ($statusID) {
            $tasks->where('status_id', '=', $statusID);
        }

        if ($allocatedUserID > 0) {
            $tasks->where('allocated_user_id', '=', $allocatedUserID);
        }

        if ($allocatedUserID === 0) {
            $tasks->where('allocated_user_id', '=', '');
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

    public function getTasksByProjectID($projectID)
    {
        return Task::where('project_id', '=', $projectID)->get();
    }

    public function getTasksByPhaseID($phaseID)
    {
        return Task::where('phase_id', '=', $phaseID)->get();
    }

    public function getTasksPaginatedList($userID, $limit, $projectID = null, $statusID = null, $allocatedUserID = null)
    {
        return $this->getTasksList($userID, $projectID, $statusID, $allocatedUserID)->paginate($limit);
    }

    public function persistTask(TaskEntity $task)
    {
        if (!isset($task->id)) {
            $taskModel = new Task();
            $taskID = Uuid::uuid4()->toString();
            $taskModel->id = $taskID;
            $task->id = $taskID;
        } else {
            $taskModel = Task::find($task->id);
        }
        $taskModel->title = $task->title;
        $taskModel->description = $task->description;
        $taskModel->estimated_time_days = $task->estimatedTimeDays;
        $taskModel->estimated_time_hours = $task->estimatedTimeHours;
        $taskModel->spent_time_days = $task->spentTimeDays;
        $taskModel->spent_time_hours = $task->spentTimeHours;
        $taskModel->phase_id = $task->phaseID;
        $taskModel->project_id = $task->projectID;
        $taskModel->status_id = $task->statusID;
        $taskModel->allocated_user_id = $task->allocatedUserID;

        $taskModel->save();

        return $task;
    }

    public function deleteTask($taskID)
    {
        $task = $this->getTaskModel($taskID);
        $task->delete();
    }

    public function deleteTasksByPhaseID($phaseID)
    {
        Task::where('phase_id', $phaseID)->delete();
    }

    private function getTaskEntity($taskModel)
    {
        if (!$taskModel) {
            return false;
        }

        $task = new TaskEntity();
        $task->id = $taskModel->id;
        $task->title = $taskModel->title;
        $task->description = $taskModel->description;
        $task->estimatedTimeDays = $taskModel->estimated_time_days;
        $task->estimatedTimeHours = $taskModel->estimated_time_hours;
        $task->spentTimeDays = $taskModel->spent_time_days;
        $task->spentTimeHours = $taskModel->spent_time_hours;
        $task->projectID = $taskModel->project_id;
        $task->phaseID = $taskModel->phase_id;
        $task->statusID = $taskModel->status_id;
        $task->allocatedUserID = $taskModel->allocated_user_id;

        return $task;
    }
}