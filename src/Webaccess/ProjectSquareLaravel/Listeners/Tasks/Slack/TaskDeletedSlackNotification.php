<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Tasks\Slack;

use Webaccess\ProjectSquare\Events\Tasks\DeleteTaskEvent;
use Webaccess\ProjectSquareLaravel\Models\Task;
use Webaccess\ProjectSquareLaravel\Tools\SlackTool;
use Webaccess\ProjectSquareLaravel\Tools\StringTool;

class TaskDeletedSlackNotification
{
    public function handle(DeleteTaskEvent $event)
    {
        if (isset($event->task) && $event->task) {
            if ($task = Task::where('id', '=', $event->task->id)->with('project', 'project.client', 'allocated_user')->first()) {
                $lines = [
                    '*Tâche :* `<' . route('tasks_edit', ['uuid' => $task->id]) . '|' . StringTool::getShortID($task->id) . '>` <' . route('tasks_edit', ['uuid' => $task->id]) . '|*' . $task->title . '*>',
                    (isset($task->allocated_user) && $task->allocated_user->id) ? '*Utilisateur assigné :* ' . $task->allocated_user->complete_name : '',
                ];

                $settingSlackChannel = app()->make('SettingManager')->getSettingByKeyAndProject('SLACK_CHANNEL', $task->project->id);

                SlackTool::send(
                    ((isset($task->project) && isset($task->project->client)) ? '<' . route('project_index', ['id' => $task->project->id]) . '|*['.$task->project->client->name.'] '.$task->project->name . '*>' : '') . ' *Une tâche a été supprimée*',
                    implode("\n", $lines),
                    '',
                    route('project_tasks', ['uuid' => $task->project->id]),
                    ($settingSlackChannel) ? $settingSlackChannel->value : '',
                    (isset($task->project) && $task->project->color != '') ? $task->project->color : '#36a64f'
                );
            }
        }
    }
}
