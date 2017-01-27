<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Ramsey\Uuid\Uuid;
use Webaccess\ProjectSquareLaravel\Models\Project;
use Webaccess\ProjectSquareLaravel\Models\User;
use Webaccess\ProjectSquare\Repositories\ProjectRepository;
use Webaccess\ProjectSquare\Entities\Project as ProjectEntity;

class EloquentProjectRepository implements ProjectRepository
{
    public function getProjectModel($projectID)
    {
        return Project::find($projectID);
    }

    public function getProject($projectID)
    {
        if ($projectModel = $this->getProjectModel($projectID)) {
            $project = new ProjectEntity();
            $project->id = $projectModel->id;
            $project->clientID = $projectModel->client_id;
            $project->name = $projectModel->name;
            $project->color = $projectModel->color;
            $project->tasksScheduledTime = $projectModel->tasks_scheduled_time;
            $project->ticketsScheduledTime = $projectModel->tickets_scheduled_time;
            $project->websiteFrontURL = $projectModel->website_front_url;
            $project->websiteBackURL = $projectModel->website_back_url;
            $project->createdAt = $projectModel->created_at;
            $project->udpatedAt = $projectModel->updated_at;

            return $project;
        }

        return false;
    }

    public function getProjects()
    {
        return Project::with('client')->get();
    }

    public function getUserProjects($userID)
    {
        //@TODO : Load clients
        return User::find($userID)->projects;
    }

    public function getProjectWithUsers($projectID)
    {
        return Project::with('users')->find($projectID);
    }

    public function getProjectsByClientID($clientID)
    {
        return Project::where('client_id', '=', $clientID)->get();
    }

    public function getProjectsPaginatedList($limit)
    {
        return Project::with('client')->orderBy('updated_at', 'DESC')->paginate($limit);
    }

    public function createProject($name, $clientID, $websiteFrontURL, $websiteBackURL, $color, $tasksScheduledTime, $ticketsScheduledTime)
    {
        $project = new Project();
        $projectID =  Uuid::uuid4()->toString();
        $project->id = $projectID;
        $project->save();
        $this->updateProject($projectID, $name, $clientID, $websiteFrontURL, $websiteBackURL, $color, $tasksScheduledTime, $ticketsScheduledTime);

        return $project->id;
    }

    public function persistProject(ProjectEntity $project)
    {
        if (!isset($project->id)) {
            $projectModel = new Project();
            $projectID = Uuid::uuid4()->toString();
            $projectModel->id = $projectID;
            $project->id = $projectID;
        } else {
            $projectModel = Project::find($project->id);
        }
        $projectModel->name = $project->name;
        $projectModel->client_id = $project->clientID;
        $projectModel->color = $project->color;
        $projectModel->tasks_scheduled_time = $project->tasksScheduledTime;
        $projectModel->tickets_scheduled_time = $project->ticketsScheduledTime;
        $projectModel->website_front_url = $project->website_front_url;
        $projectModel->website_back_url = $project->website_back_url;

        $projectModel->save();

        return $project;
    }

    public function updateProject($projectID, $name, $clientID, $websiteFrontURL, $websiteBackURL, $color, $tasksScheduledTime, $ticketsScheduledTime)
    {
        if ($project = $this->getProjectModel($projectID)) {
            $project->name = $name;
            $project->client_id = $clientID;
            $project->website_front_url = $websiteFrontURL;
            $project->website_back_url = $websiteBackURL;
            $project->color = $color;
            $project->tickets_scheduled_time = ($ticketsScheduledTime != "") ? $ticketsScheduledTime : 0;
            $project->tasks_scheduled_time = ($tasksScheduledTime != "") ? $tasksScheduledTime : 0;
            $project->save();
        }
    }

    public function deleteProject($projectID)
    {
        $project = $this->getProjectModel($projectID);
        $project->users()->detach();
        $project->delete();
    }

    public static function deleteProjectByClientID($clientID)
    {
        $project = Project::where('client_id', '=', $clientID);
        $project->users()->detach();
        $project->delete();
    }

    public function addUserToProject($projectID, $userID, $roleID)
    {
        $project = $this->getProjectModel($projectID);
        $project->users()->attach($userID, ['role_id' => $roleID]);
    }

    public function isUserInProject($projectID, $userID)
    {
        $user = User::find($userID);
        $project = $this->getProjectModel($projectID);

        return count($project->users()->where('user_id', '=', $userID)->get()) > 0 || $user->client_id == $project->client_id;
    }

    public function removeUserFromProject($projectID, $userID)
    {
        $project = $this->getProjectModel($projectID);
        $project->users()->detach($userID);
    }
}
