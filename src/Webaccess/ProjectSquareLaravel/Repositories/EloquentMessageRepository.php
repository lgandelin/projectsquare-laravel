<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Ramsey\Uuid\Uuid;
use Webaccess\ProjectSquare\Entities\Message as MessageEntity;
use Webaccess\ProjectSquare\Repositories\MessageRepository;
use Webaccess\ProjectSquareLaravel\Models\Message;

class EloquentMessageRepository implements MessageRepository
{
    public function getMessage($messageID)
    {
        $messageModel = $this->getMessageModel($messageID);

        if (!$messageModel) {
            return false;
        }

        $message = new MessageEntity();
        $message->id = $messageModel->id;
        $message->content = $messageModel->content;
        $message->userID = $messageModel->user_id;
        $message->conversationID = $messageModel->conversation_id;

        return $message;
    }

    public function getMessageModel($messageID)
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

            $result[] = $message;
        }

        return $result;
    }

    public function persistMessage(MessageEntity $message)
    {
        if (!isset($message->id)) {
            $messageModel = new Message();
            $modelID = Uuid::uuid4()->toString();
            $messageModel->id = $modelID;
            $message->id = $modelID;
        } else {
            $messageModel = Message::find($message->id);
        }
        $messageModel->content = $message->content;
        $messageModel->user_id = $message->userID;
        $messageModel->conversation_id = $message->conversationID;
        $messageModel->save();

        return $message;
    }
}
