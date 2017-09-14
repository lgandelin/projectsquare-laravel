<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Tasks\Slack;

use Webaccess\ProjectSquare\Events\Tasks\UpdateTaskEvent;
use Webaccess\ProjectSquareLaravel\Models\Task;
use Webaccess\ProjectSquareLaravel\Tools\SlackTool;
use Webaccess\ProjectSquareLaravel\Tools\StringTool;

class TaskUpdatedSlackNotification
{
    public function handle(UpdateTaskEvent $event)
    {
        if (isset($event->taskID) && $event->taskID) {
            if ($task = Task::where('id', '=', $event->taskID)->with('project', 'project.client', 'allocated_user')->first()) {
                $lines = [
                    '*Tâche :* `<' . route('tasks_edit', ['uuid' => $task->id]) . '|' . StringTool::getShortID($task->id) . '>` <' . route('tasks_edit', ['uuid' => $task->id]) . '|*' . $task->title . '*>',
                    (isset($task->status_id) && $task->status_id != "") ? '*Statut :* ' . trans('projectsquare::tasks.state_' . $task->status_id) : '',
                ];

                if (isset($task->allocated_user) && $task->allocated_user) {
                    $lines[] = '*Utilisateur assigné :* ' . $task->allocated_user->complete_name;
                }

                $settingSlackChannel = app()->make('SettingManager')->getSettingByKeyAndProject('SLACK_CHANNEL', $task->project->id);

                SlackTool::send(
                    ((isset($task->project) && isset($task->project->client)) ? '<' . route('project_tasks', ['id' => $task->project->id]) . '|*['.$task->project->client->name.'] '.$task->project->name . '*>' : '') . ' *Une tâche a été mise à jour*',
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
