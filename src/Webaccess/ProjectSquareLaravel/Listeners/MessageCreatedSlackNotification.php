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
        $message = (new EloquentMessageRepository())->getMessage($message->id);

        $lines = [
            'Projet : *['.$message->conversation->project->client->name.'] '.$message->conversation->project->name.'*',
            'Auteur : *'.$message->user->complete_name.'*',
            'Message : '.$message->content,
            route('conversation', ['id' => $message->conversation->id]),
        ];

        $settingSlackChannel = app()->make('SettingManager')->getSettingByKeyAndProject('SLACK_CHANNEL', $message->conversation->project->id);

        SlackTool::send(
            'Nouveau message dans la conversation : '.$message->conversation->title,
            implode("\n", $lines),
            $message->user->complete_name,
            route('conversation', ['id' => $message->conversation->id]),
            ($settingSlackChannel) ? $settingSlackChannel->value : '',
            '#32B1DB'
        );
    }
}
