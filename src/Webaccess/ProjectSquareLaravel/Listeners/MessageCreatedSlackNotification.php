<?php

namespace Webaccess\ProjectSquareLaravel\Listeners;

use Webaccess\ProjectSquare\Events\Messages\CreateMessageEvent;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentMessageRepository;
use Webaccess\ProjectSquareLaravel\Tools\SlackTool;

class MessageCreatedSlackNotification
{
    public function handle(CreateMessageEvent $event)
    {
        $message = $event->message;
        if ($message = (new EloquentMessageRepository())->getMessageModel($message->id) && isset($message->conversation) && isset($message->user)) {

            $lines = [
                (isset($message->conversation->project) && isset($message->conversation->project->client)) ? 'Projet : *[' . $message->conversation->project->client->name . '] ' . $message->conversation->project->name . '*' : '',
                'Auteur : *' . $message->user->complete_name . '*',
                'Message : ' . $message->content,
                route('conversations_view', ['id' => $message->conversation->id]),
            ];

            $settingSlackChannel = app()->make('SettingManager')->getSettingByKeyAndProject('SLACK_CHANNEL', $message->conversation->project->id);

            SlackTool::send(
                'Nouveau message dans la conversation : ' . $message->conversation->title,
                implode("\n", $lines),
                $message->user->complete_name,
                route('conversations_view', ['id' => $message->conversation->id]),
                ($settingSlackChannel) ? $settingSlackChannel->value : '',
                '#32B1DB'
            );
        }
    }
}
