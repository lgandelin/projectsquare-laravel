<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquare\Entities\Message as MessageEntity;
use Webaccess\ProjectSquare\Repositories\MessageRepository;
use Webaccess\ProjectSquareLaravel\Models\Message;

class EloquentMessageRepository implements MessageRepository
{
    public function getMessage($messageID)
    {
        return Message::find($messageID);
    }

    public function getMessagesByConversation($conversationID)
    {
        $result = [];
        $messageModels = Message::where('conversation_id', '=', $conversationID)->get();
        foreach ($messageModels as $messageModel) {
            $message = new MessageEntity();
            $message->id = $messageModel->id;
            $message->conversationID = $messageModel->conversation_id;
            $message->content = $messageModel->content;
            $message->userID = $messageModel->user_id;

            $result[]= $message;
        }

        return $result;
    }

    public function persistMessage(MessageEntity $message)
    {
        $messageModel = (!isset($message->id)) ? new Message() : Message::find($message->id);
        $messageModel->content = $message->content;
        $messageModel->user_id = $message->userID;
        $messageModel->conversation_id = $message->conversationID;
        $messageModel->save();

        $message->id = $messageModel->id;
        return $message;
    }
}
