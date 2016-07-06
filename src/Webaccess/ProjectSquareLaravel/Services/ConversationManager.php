<?php

namespace Webaccess\ProjectSquareLaravel\Services;

use Webaccess\ProjectSquareLaravel\Models\Project;
use Webaccess\ProjectSquareLaravel\Models\User;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentConversationRepository;

class ConversationManager
{
    public function __construct()
    {
        $this->repository = new EloquentConversationRepository();
    }

    public function getConversation($conversationID)
    {
        if (!$conversation = $this->repository->getConversation($conversationID)) {
            throw new \Exception(trans('projectsquare::conversations.conversation_not_found'));
        }

        return $conversation;
    }

    public function getConversationModel($conversationID)
    {
        if (!$conversation = $this->repository->getConversationModel($conversationID)) {
            throw new \Exception(trans('projectsquare::conversations.conversation_not_found'));
        }

        return $conversation;
    }

    public function getConversationsPaginatedList($userID, $limit, $projectID = null)
    {
        //Ressource projects
        $projectIDs = User::find($userID)->projects->pluck('id')->toArray();

        //Client project
        $user = User::find($userID);
        if (isset($user->client_id)) {
            $project = Project::where('client_id', '=', $user->client_id)->first();
            $projectIDs[]= $project->id;
        }

        //Filter by project
        if ($projectID != null) {
            $projectIDs = [$projectID];
        }

        return $this->repository->getConversationsPaginatedList($projectIDs, $limit);
    }

    public function getConversationsByProject($projectID, $limit = null)
    {
        return $this->repository->getConversationsByProject([$projectID], $limit);
    }

    public function getConversations($userID, $limit=null)
    {
        //Ressource projects
        $projectIDs = User::find($userID)->projects->pluck('id')->toArray();

        //Client project
        $user = User::find($userID);
        if (isset($user->client_id)) {
            $project = Project::where('client_id', '=', $user->client_id)->first();
            $projectIDs[]= $project->id;
        }

        return $this->repository->getConversationsByProject($projectIDs, $limit);
    }

    public function deleteConversationByProjectID($projectID)
    {
        return $this->repository->deleteConversationByProjectID($projectID);
    }
}
