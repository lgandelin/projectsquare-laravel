<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquare\Entities\Conversation as ConversationEntity;
use Webaccess\ProjectSquare\Repositories\ConversationRepository;
use Webaccess\ProjectSquareLaravel\Models\Conversation;

class EloquentConversationRepository implements ConversationRepository
{
    public $projectRepository;

    public function __construct()
    {
        $this->projectRepository = new EloquentProjectRepository();
    }

    public function getConversation($conversationID)
    {
        if ($conversationModel = $this->getConversationModel($conversationID)) {
            $conversation = new ConversationEntity();
            $conversation->id = $conversationModel->id;
            $conversation->title = $conversationModel->title;
            $conversation->projectID = $conversationModel->project_id;

            return $conversation;
        }

        return false;
    }

    public function getConversationModel($conversationID)
    {
        return Conversation::with('messages')->with('messages.user')->find($conversationID);
    }

    public function getConversationsPaginatedList($limit)
    {
        return Conversation::with('messages')->with('messages.user')->with('project')->with('project.client')->orderBy('created_at', 'DESC')->paginate($limit);
    }

    public function getConversationsByProject($projectsID, $limit = null)
    {
        $conversations = Conversation::with('messages')->with('messages.user')->with('project')->with('project.client')->orderBy('created_at', 'DESC')->whereIn('project_id', $projectsID);
        if ($limit) {
            $conversations->limit($limit);
        }

        return $conversations->get();
    }

    public function persistConversation(ConversationEntity $conversation)
    {
        $conversationModel = (!isset($conversation->id)) ? new Conversation() : Conversation::find($conversation->id);
        $conversationModel->title = $conversation->title;
        if ($project = $this->projectRepository->getProject($conversation->projectID)) {
            $conversationModel->project()->associate($project);
        }
        $conversationModel->save();

        $conversation->id = $conversationModel->id;

        return $conversation;
    }
}
