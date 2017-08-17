<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Messages\Slack;

use Webaccess\ProjectSquare\Events\Messages\CreateMessageEvent;
use Webaccess\ProjectSquareLaravel\Models\Message;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentMessageRepository;
use Webaccess\ProjectSquareLaravel\Tools\SlackTool;
use Webaccess\ProjectSquareLaravel\Tools\StringTool;

class MessageCreatedSlackNotification
{
    public function handle(CreateMessageEvent $event)
    {
        if (isset($event->message) && $event->message) {
            if ($message = Message::where('id', '=', $event->message->id)->with('conversation', 'conversation.project', 'conversation.project.client', 'user')->first()) {
                if (isset($message->conversation)) {
                    $lines = [
                        '*Conversation :* `<' . route('conversations_view', ['id' => $message->conversation->id]) . '|' . StringTool::getShortID($message->conversation->id) . '>` <' . route('conversations_view', ['id' => $message->conversation->id]) . '|*' . $message->conversation->title . '*>',
                        '*Auteur :* ' . $message->user->complete_name,
                        '*Message :* ' . $message->content,
                    ];

                    $settingSlackChannel = app()->make('SettingManager')->getSettingByKeyAndProject('SLACK_CHANNEL', $message->conversation->project->id);

                    SlackTool::send(
                        ((isset($message->conversation->project) && isset($message->conversation->project->client)) ? '<' . route('project_messages', ['id' => $message->conversation->project->id]) . '|*[' . $message->conversation->project->client->name . '] ' . $message->conversation->project->name . '*>' : '') . ' *Un nouveau message a été envoyé*',
                        implode("\n", $lines),
                        $message->user->complete_name,
                        route('conversations_view', ['id' => $message->conversation->id]),
                        ($settingSlackChannel) ? $settingSlackChannel->value : '',
                        (isset($message->conversation->project) && $message->conversation->project->color != '') ? $message->conversation->project->color : '#36a64f'
                    );
                }
            }
        }
    }
}
