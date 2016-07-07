<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

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
        $projectModel = $this->getProjectModel($projectID);

        $project = new ProjectEntity();
        $project->id = $projectModel->id;
        $project->clientID = $projectModel->client_id;
        $project->name = $projectModel->name;
        $project->status = $projectModel->status;
        $project->color = $projectModel->color;
        $project->websiteFrontURL = $projectModel->website_front_url;
        $project->websiteBackURL = $projectModel->website_back_url;
        $project->createdAt = $projectModel->created_at;
        $project->udpatedAt = $projectModel->updated_at;

        return $project;
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

    public function getProjectsByClient($clientID)
    {
        return Project::where('client_id', '=', $clientID)->get();
    }

    public function getProjectsPaginatedList($limit)
    {
        return Project::with('client')->orderBy('updated_at', 'DESC')->paginate($limit);
    }

    public function createProject($name, $clientID, $websiteFrontURL, $websiteBackURL, $refererID, $status, $color)
    {
        $project = new Project();
        $project->save();
        $this->updateProject($project->id, $name, $clientID, $websiteFrontURL, $websiteBackURL, $refererID, $status, $color);

        return $project->id;
    }

    public function updateProject($projectID, $name, $clientID, $websiteFrontURL, $websiteBackURL, $refererID, $status, $color)
    {
        $project = $this->getProject($projectID);
        $project->name = $name;
        $project->client_id = $clientID;
        $project->website_front_url = $websiteFrontURL;
        $project->website_back_url = $websiteBackURL;
        if ($refererID) {
            $project->referer_id = $refererID;
        }
        $project->status = $status;
        $project->color = $color;
        $project->save();
    }

    public function deleteProject($projectID)
    {
        $project = $this->getProject($projectID);
        $project->delete();
    }

    public static function deleteProjectByClientID($clientID)
    {
        Project::where('client_id', '=', $clientID)->delete();
    }

    public function addUserToProject($project, $userID, $roleID)
    {
        $project->users()->attach($userID, ['role_id' => $roleID]);
    }

    public function isUserInProject($project, $userID)
    {
        $user = User::find($userID);
        $projectModel = Project::find($project->id);

        return count($projectModel->users()->where('user_id', '=', $userID)->get()) > 0 || $user->client_id == $projectModel->client_id;
    }

    public function removeUserFromProject($project, $userID)
    {
        $project->users()->detach($userID);
    }
}
