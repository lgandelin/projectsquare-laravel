<?php

namespace Webaccess\ProjectSquareLaravel\Services;

use Webaccess\ProjectSquare\Requests\Users\RemoveUserFromProjectRequest;
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

    private function getProjectsByClientID($clientID)
    {
        return $this->repository->getProjectsByClientID($clientID);
    }

    public function getProjectWithUsers($projectID)
    {
        if (!$project = $this->repository->getProjectWithUsers($projectID)) {
            throw new \Exception(trans('projectsquare::projects.project_not_found'));
        }

        foreach ($project->users as $user) {
            if (isset($user->pivot) && isset($user->pivot->role_id)) {
                $user->role = RoleManager::getRole($user->pivot->role_id);
            }
        }

        return $project;
    }

    public function getProjectsPaginatedList($itemsPerPage, $sortColumn, $sortOrder)
    {
        return $this->repository->getProjectsPaginatedList($itemsPerPage, $sortColumn, $sortOrder);
    }

    public function deleteProject($projectID)
    {
        (new ConversationManager())->deleteConversationByProjectID($projectID);
        (new AlertManager())->deleteAlertByProjectID($projectID);
        $this->repository->deleteProject($projectID);
    }

    public function deleteProjectByClient($clientID)
    {
        $projects = (new ProjectManager())->getProjectsByClientID($clientID);
        foreach ($projects as $project) {
            $this->deleteProject($project->id);
        }
    }

    public function isUserInProject($projectID, $userID)
    {
        return $this->repository->isUserInProject($projectID, $userID);
    }

    public function removeUserFromProject($projectID, $userID, $requesterUserID)
    {
        app()->make('RemoveUserFromProjectInteractor')->execute(new RemoveUserFromProjectRequest([
            'userID' => $userID,
            'projectID' => $projectID,
            'requesterUserID' => $requesterUserID,
        ]));
    }
}
