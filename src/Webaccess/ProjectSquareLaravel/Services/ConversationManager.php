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

    public function getConversationsPaginatedList($userID, $projectID = null, $limit = null)
    {
        return $this->repository->getConversationsPaginatedList($userID, $projectID, $limit);
    }

    public function getConversationsByProject($projectID, $limit = null)
    {
        return $this->repository->getConversationsByProject([$projectID], $limit);
    }

    public function deleteConversationByProjectID($projectID)
    {
        return $this->repository->deleteConversationByProjectID($projectID);
    }
}
