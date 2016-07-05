<?php

namespace Webaccess\ProjectSquareLaravel\Services;

use Webaccess\ProjectSquareLaravel\Repositories\EloquentConversationRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentProjectRepository;

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

    public function getConversationsPaginatedList()
    {
        return $this->repository->getConversationsPaginatedList(env('CONVERSATIONS_PER_PAGE', 10));
    }

    public function getConversationsByProject($projectID, $limit = null)
    {
        return $this->repository->getConversationsByProject([$projectID], $limit);
    }

    public function getLastConversations($userID, $limit)
    {
        $projectsID = (new EloquentProjectRepository())->getUserProjects($userID)->pluck('id')->toArray();

        return $this->repository->getConversationsByProject($projectsID, $limit);
    }

    public function deleteConversationByProjectID($projectID)
    {
        return $this->repository->deleteConversationByProjectID($projectID);
    }
}
