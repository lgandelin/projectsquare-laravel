<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Tasks\Slack;

use Webaccess\ProjectSquare\Events\Tasks\CreateTaskEvent;
use Webaccess\ProjectSquareLaravel\Models\Task;
use Webaccess\ProjectSquareLaravel\Tools\SlackTool;
use Webaccess\ProjectSquareLaravel\Tools\StringTool;

class TaskCreatedSlackNotification
{
    public function handle(CreateTaskEvent $event)
    {
        if (isset($event->taskID) && $event->taskID) {
            if ($task = Task::where('id', '=', $event->taskID)->with('project', 'project.client', 'allocated_user')->first()) {
                $lines = [
                    '*Tâche :* `<' . route('tasks_edit', ['uuid' => $task->id]) . '|' . StringTool::getShortID($task->id) . '>` <' . route('tasks_edit', ['uuid' => $task->id]) . '|*' . $task->title . '*>',
                    (isset($task->allocated_user) && $task->allocated_user->id) ? '*Utilisateur assigné :* ' . $task->allocated_user->complete_name : '',
                    '*Description :* ' . $task->description,
                ];

                $settingSlackChannel = app()->make('SettingManager')->getSettingByKeyAndProject('SLACK_CHANNEL', $task->projectID);

                SlackTool::send(
                    ((isset($task->project) && isset($task->project->client)) ? '<' . route('project_index', ['id' => $task->project->id]) . '|*['.$task->project->client->name.'] '.$task->project->name . '*>' : '') . ' *Une nouvelle tâche a été créée*',
                    implode("\n", $lines),
                    '',
                    route('tasks_edit', ['uuid' => $task->id]),
                    ($settingSlackChannel) ? $settingSlackChannel->value : '',
                    (isset($task->project) && $task->project->color != '') ? $task->project->color : '#36a64f'
                );
            }
        }
    }
}
