<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Ramsey\Uuid\Uuid;
use Webaccess\ProjectSquare\Repositories\TaskRepository;
use Webaccess\ProjectSquare\Entities\Project as ProjectEntity;
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
        return Task::with('phase', 'project')->find($taskID);
    }

    public function getTasks($userID, $projectID = null, $statusID = null, $allocatedUserID = null, $phaseID = null, $entities = false)
    {
        $tasks = $this->getTasksList($userID, $projectID, $statusID, $allocatedUserID, $phaseID, null, null, $entities);

        return $entities ? $tasks : $tasks->get();
    }

    public function getTasksList($userID, $projectID = null, $statusID = null, $allocatedUserID = null, $phaseID = null, $sortColumn = null, $sortOrder = null, $entities = false)
    {
        $projectIDs = [];

        //Ressource projects
        $user = User::find($userID);
        if ($user && $user->projects) {
            $projects = $user->projects()->where('status_id', '=', ProjectEntity::IN_PROGRESS);
            $projectIDs = $projects->pluck('id')->toArray();
        }

        //Client project
        if (isset($user->client_id)) {
            $project = Project::where('client_id', '=', $user->client_id)->where('status_id', '=', ProjectEntity::IN_PROGRESS)->orderBy('created_at', 'DESC')->first();

            $projectIDs[]= $project->id;
        }

        $tasks = Task::whereIn('project_id', $projectIDs)->with('project', 'project.client')->with('project.client')->with('phase');

        if ($projectID) {
            $tasks->where('project_id', '=', $projectID);
        }

        if ($statusID) {
            $tasks->where('tasks.status_id', '=', $statusID);
        } else {
            $tasks->where('tasks.status_id', '!=', TaskEntity::COMPLETED);
        }

        if ($allocatedUserID > 0) {
            $tasks->where('allocated_user_id', '=', $allocatedUserID);
        }

        if ($allocatedUserID === 0) {
            $tasks->where('allocated_user_id', '=', '');
        }

        if ($phaseID) {
            $tasks->where('phase_id', '=', $phaseID);
        }

        if ($sortColumn == 'client') {
            $tasks->join('projects', 'projects.id', '=', 'tasks.project_id')
                ->join('clients', 'clients.id', '=', 'projects.client_id')->orderBy('clients.name', $sortOrder ? $sortOrder : 'DESC');
        } else {
            $tasks->orderBy($sortColumn ? $sortColumn : 'updated_at', $sortOrder ? $sortOrder : 'DESC');
        }

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
        $tasks = Task::with('allocated_user')->where('phase_id', '=', $phaseID)->orderBy('order', 'asc')->get();

        $result = [];
        foreach ($tasks as $taskModel) {
            $task = $this->getTaskEntity($taskModel);
            if ($taskModel->allocated_user) {
                $task->allocatedUser = EloquentUserRepository::getEntityFromModel($taskModel->allocated_user);
            }
            $result[]= $task;
        }

        return $result;
    }

    public function getTasksPaginatedList($userID, $limit, $projectID = null, $statusID = null, $allocatedUserID = null, $phaseID = null, $sortColumn = null, $sortOrder = null)
    {
        return $this->getTasksList($userID, $projectID, $statusID, $allocatedUserID, $phaseID, $sortColumn, $sortOrder)->paginate($limit);
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
        $taskModel->order = $task->order;

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
        $task->project = $taskModel->project;
        $task->projectID = $taskModel->project_id;
        $task->phaseID = $taskModel->phase_id;
        $task->statusID = $taskModel->status_id;
        $task->allocatedUserID = $taskModel->allocated_user_id;
        $task->order = $taskModel->order;

        if ($taskModel->phase) {
            $task->phaseName = $taskModel->phase->name;
        }

        return $task;
    }
}