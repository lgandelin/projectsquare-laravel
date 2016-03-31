<?php

namespace Webaccess\ProjectSquareLaravel\Listeners;

use Webaccess\ProjectSquare\Events\Messages\CreateConversationEvent;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentConversationRepository;
use Webaccess\ProjectSquareLaravel\Tools\SlackTool;

class ConversationCreatedSlackNotification
{
    public function handle(CreateConversationEvent $event)
    {
        $conversation = (new EloquentConversationRepository())->getConversationModel($event->conversation->id);

        $lines = [
            'Projet : *['.$conversation->project->client->name.'] '.$conversation->project->name.'*',
            'Titre : *'.$conversation->title.'*',
            'Auteur : *'.$conversation->messages[0]->user->complete_name.'*',
            'Message : '.$conversation->messages[0]->content,
            route('conversation', ['id' => $conversation->id]),
        ];

        $settingSlackChannel = app()->make('SettingManager')->getSettingByKeyAndProject('SLACK_CHANNEL', $conversation->project->id);

        SlackTool::send(
            'Nouvelle conversation : '.$conversation->title,
            implode("\n", $lines),
            $conversation->messages[0]->user->complete_name,
            route('conversation', ['id' => $conversation->id]),
            ($settingSlackChannel) ? $settingSlackChannel->value : '',
            '#32B1DB'
        );
    }
}
