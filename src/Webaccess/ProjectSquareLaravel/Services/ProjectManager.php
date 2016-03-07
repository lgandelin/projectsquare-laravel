<?php

namespace Webaccess\ProjectSquareLaravel\Services;

use Webaccess\ProjectSquareLaravel\Repositories\EloquentProjectRepository;

class ProjectManager
{
    public function __construct()
    {
        $this->repository = new EloquentProjectRepository();
    }
    
    public function getProject($projectID)
    {
        if (!$project = $this->repository->getProject($projectID)) {
            throw new \Exception(trans('projectsquare::projects.project_not_found'));
        }

        return $project;
    }

    public function getProjects()
    {
        return $this->repository->getProjects();
    }

    public function getUserProjects($userID)
    {
        return $this->repository->getUserProjects($userID);
    }

    public function getProjectWithUsers($projectID)
    {
        if (!$project = $this->repository->getProjectWithUsers($projectID)) {
            throw new \Exception(trans('projectsquare::projects.project_not_found'));
        }

        foreach ($project->users as $user) {
            $user->role = RoleManager::getRole($user->pivot->role_id);
        }

        return $project;
    }

    public function getProjectsPaginatedList()
    {
        return $this->repository->getProjectsPaginatedList(env('PROJECTS_PER_PAGE', 10));
    }

    public function createProject($name, $clientID, $websiteFrontURL, $websiteBackURL, $refererID, $status)
    {
        $this->repository->createProject($name, $clientID, $websiteFrontURL, $websiteBackURL, $refererID, $status);
    }

    public function updateProject($projectID, $name, $clientID, $websiteFrontURL, $websiteBackURL, $status)
    {
        $this->repository->updateProject($projectID, $name, $clientID, $websiteFrontURL, $websiteBackURL, null, $status);
    }

    public function deleteProject($projectID)
    {
        $this->repository->deleteProject($projectID);
    }

    public function addUserToProject($project, $userID, $roleID)
    {
        if ($this->isUserInProject($project, $userID)) {
            throw new \Exception(trans('projectsquare::projects.user_already_in_project'));
        }

        $this->repository->addUserToProject($project, $userID, $roleID);
    }

    public function isUserInProject($project, $userID)
    {
        return $this->repository->isUserInProject($project, $userID);
    }

    public function removeUserFromProject($project, $userID)
    {
        $this->repository->removeUserFromProject($project, $userID);
    }
}
