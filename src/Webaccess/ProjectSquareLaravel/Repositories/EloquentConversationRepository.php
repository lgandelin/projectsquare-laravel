<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquare\Entities\Conversation as ConversationEntity;
use Webaccess\ProjectSquare\Repositories\ConversationRepository;
use Webaccess\ProjectSquareLaravel\Models\Conversation;
use Webaccess\ProjectSquareLaravel\Models\Message;
use Webaccess\ProjectSquareLaravel\Services\UserManager;

class EloquentConversationRepository implements ConversationRepository
{
    public $projectRepository;

    public function __construct()
    {
        $this->projectRepository = new EloquentProjectRepository();
    }

    public function getConversation($conversationID)
    {
        $conversationModel = $this->getConversationModel($conversationID);

        $conversation = new ConversationEntity();
        $conversation->id = $conversationModel->id;
        $conversation->title = $conversationModel->title;
        $conversation->projectID = $conversationModel->project_id;

        return $conversation;
    }

    public function getConversationModel($conversationID)
    {
        return Conversation::with('messages')->with('messages.user')->find($conversationID);
    }

    public function getLastConversations($limit)
    {
        return Conversation::with('messages')->orderBy('created_at', 'DESC')->limit($limit)->get();
    }

    public function getConversationsPaginatedList($limit)
    {
        return Conversation::with('messages')->orderBy('created_at', 'DESC')->paginate($limit);
    }

    public function getConversationsByProject($projectID)
    {
        return Conversation::with('messages')->orderBy('created_at', 'DESC')->where('project_id', '=', $projectID)->get();
    }

    public function createConversation($projectID, $title, $userID, $content, $attachedUserIDs)
    {
        $conversation = new Conversation();
        $conversation->title = $title;
        $conversation->project_id = $projectID;
        $conversation->save();

        $message = new Message();
        $message->content = $content;
        $message->user_id = $userID;
        $message->save();

        $message->conversation()->associate($conversation);
        $message->save();

        //Attach the author to the conversation
        if ($user = UserManager::getUser($userID)) {
            $conversation->users()->attach($user);
        }

        //Attach the other users to the conversation
        foreach ($attachedUserIDs as $userID) {
            if ($user = UserManager::getUser($userID) && !self::isUserAlreadyAttachedToConversation($user, $conversation)) {
                $conversation->users()->attach($user);
            }
        }

        return $conversation;
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

    private function isUserAlreadyAttachedToConversation($user, $conversation)
    {
        foreach ($conversation->users as $userInConversation) {
            return $userInConversation->id == $user->id;
        }

        return false;
    }
}
