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
        return Project::with('client')->find($projectID);
    }

    public function getProject($projectID)
    {
        if ($projectModel = $this->getProjectModel($projectID)) {
            $project = new ProjectEntity();
            $project->id = $projectModel->id;
            $project->clientID = $projectModel->client_id;
            $project->name = $projectModel->name;
            $project->color = $projectModel->color;
            $project->statusID = $projectModel->status_id;
            $project->websiteFrontURL = $projectModel->website_front_url;
            $project->websiteBackURL = $projectModel->website_back_url;
            $project->createdAt = $projectModel->created_at;
            $project->udpatedAt = $projectModel->updated_at;
            $project->clientName = isset($projectModel->client) && isset($projectModel->client->name) ? $projectModel->client->name : "";

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

    public function getProjectsPaginatedList($limit, $sortColumn = null, $sortOrder = null)
    {
        return Project::with('client')->orderBy($sortColumn ? $sortColumn : 'updated_at', $sortOrder ? $sortOrder : 'DESC')->paginate($limit);
    }

    public function getCurrentProjects($userID)
    {
        $projects = [];
        $user = User::find($userID);


        if (isset($user->client_id) && $user->client_id != null) {
            $projects = Project::where('client_id', '=', $user->client_id)->where('status_id', '=', ProjectEntity::IN_PROGRESS)->orderBy('created_at', 'DESC')->get();
        } else {
            foreach (Project::orderBy('created_at', 'desc')->get() as $project) {
                foreach ($project->users as $user) {
                    if ($user->id == $userID) {
                        if ($project->status_id == ProjectEntity::IN_PROGRESS) {
                            $projects[]= $project;
                        }
                    }
                }
            }
        }

        return $projects;
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
        $projectModel->status_Id = $project->statusID;
        $projectModel->website_front_url = $project->websiteFrontURL;
        $projectModel->website_back_url = $project->websiteBackURL;

        $projectModel->save();

        return $project;
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

        if (
            $project->client_id != null && $user->client_id == $project->client_id ||
            count($project->users()->where('user_id', '=', $userID)->get()) > 0
        ) {
            return true;
        }

        return false;
    }

    public function removeUserFromProject($projectID, $userID)
    {
        $project = $this->getProjectModel($projectID);
        $project->users()->detach($userID);
    }
}
