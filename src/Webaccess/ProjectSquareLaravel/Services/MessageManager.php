<?php

namespace Webaccess\ProjectSquareLaravel\Services;

use Webaccess\ProjectSquareLaravel\Repositories\EloquentMessageRepository;

class MessageManager
{
    public static function reply($conversationID, $userID, $content)
    {
        $conversation = ConversationManager::getConversation($conversationID);
        $message = EloquentMessageRepository::createMessage($userID, $content);
        $message->conversation()->associate($conversation);
        $message->save();

        return $message;
    }

    public static function getMessagesByConversation($conversationID)
    {
        return ConversationManager::getConversation($conversationID)->messages()->get();
    }
}
