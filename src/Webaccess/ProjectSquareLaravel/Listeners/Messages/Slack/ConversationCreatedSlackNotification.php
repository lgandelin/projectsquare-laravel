<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Messages\Slack;

use Webaccess\ProjectSquare\Events\Messages\CreateConversationEvent;
use Webaccess\ProjectSquareLaravel\Models\Conversation;
use Webaccess\ProjectSquareLaravel\Tools\SlackTool;
use Webaccess\ProjectSquareLaravel\Tools\StringTool;

class ConversationCreatedSlackNotification
{
    public function handle(CreateConversationEvent $event)
    {
        if (isset($event->conversation) && $event->conversation) {
            if ($conversation = Conversation::where('id', '=', $event->conversation->id)->with('project', 'messages', 'messages.user', 'users')->first()) {

                $lines = [
                    '*Conversation :* `<' . route('conversations_view', ['id' => $conversation->id]) . '|' . StringTool::getShortID($conversation->id) . '>` <' . route('conversations_view', ['id' => $conversation->id]) . '|*' . $conversation->title . '*>',
                    '*Titre :* ' . $conversation->title,
                    '*Auteur :* ' . $conversation->messages[0]->user->complete_name,
                    '*Message :* ' . $conversation->messages[0]->content,
                ];

                $settingSlackChannel = app()->make('SettingManager')->getSettingByKeyAndProject('SLACK_CHANNEL', $conversation->project->id);

                SlackTool::send(
                    ((isset($conversation->project) && isset($conversation->project->client)) ? '<' . route('project_index', ['id' => $conversation->project->id]) . '|*[' . $conversation->project->client->name . '] ' . $conversation->project->name . '*>' : '') . ' *Une nouvelle conversation a été créée*',
                    implode("\n", $lines),
                    $conversation->messages[0]->user->complete_name,
                    route('conversations_view', ['id' => $conversation->id]),
                    ($settingSlackChannel) ? $settingSlackChannel->value : '',
                    '#32B1DB'
                );
            }
        }
    }
}
