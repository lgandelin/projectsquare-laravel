<?php

namespace Webaccess\ProjectSquareLaravel\Services;

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

    public function getConversationsPaginatedList()
    {
        return $this->repository->getConversationsPaginatedList(env('CONVERSATIONS_PER_PAGE', 10));
    }

    public function getConversationsByProject($projectID)
    {
        return $this->repository->getConversationsByProject($projectID);
    }

    public function getLastConversations($limit)
    {
        return $this->repository->getLastConversations($limit);
    }
}
