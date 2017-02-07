<?php

namespace Webaccess\ProjectSquareLaravel\Listeners;

use Webaccess\ProjectSquare\Events\Tickets\DeleteTicketEvent;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentProjectRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentTicketRepository;
use Webaccess\ProjectSquareLaravel\Tools\SlackTool;

class TicketDeletedSlackNotification
{
    public function handle(DeleteTicketEvent $event)
    {
        $ticket = (new EloquentTicketRepository())->getTicketWithStates($event->ticket->id);
        $project = (new EloquentProjectRepository())->getProjectModel($ticket->projectID);

        $lines = [
            (isset($project) && isset($project->client)) ? '*Projet :* ['.$project->client->name.'] '.$project->name : '',
        ];

        $settingSlackChannel = app()->make('SettingManager')->getSettingByKeyAndProject('SLACK_CHANNEL', $ticket->projectID);

        SlackTool::send(
            'Ticket supprimé : '.$ticket->title,
            implode("\n", $lines),
            (isset($ticket->last_state) && isset($ticket->last_state->author_user)) ? $ticket->last_state->author_user->complete_name : '',
            null,
            ($settingSlackChannel) ? $settingSlackChannel->value : '',
            (isset($project) && $project->color != '') ? $project->color : '#36a64f'
        );
    }
}
