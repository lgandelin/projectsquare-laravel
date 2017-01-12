<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Ramsey\Uuid\Uuid;
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

    public function getConversationsPaginatedList($projectsID, $limit)
    {
        $conversations = Conversation::with('messages')->with('messages.user')->with('project')->with('project.client')->orderBy('created_at', 'DESC')->whereIn('project_id', $projectsID);

        return $conversations->paginate($limit);
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
        if (!isset($conversation->id)) {
            $conversationModel = new Conversation();
            $conversationID = Uuid::uuid4()->toString();
            $conversationModel->id = $conversationID;
            $conversation->id = $conversationID;
        } else {
            Conversation::find($conversation->id);
        }
        $conversationModel->title = $conversation->title;
        if ($project = $this->projectRepository->getProjectModel($conversation->projectID)) {
            $conversationModel->project()->associate($project);
        }
        $conversationModel->save();

        $conversation->id = $conversationModel->id;

        return $conversation;
    }

    public function deleteConversationByProjectID($projectID)
    {
        Conversation::where('project_id', '=', $projectID)->delete();
    }
}
