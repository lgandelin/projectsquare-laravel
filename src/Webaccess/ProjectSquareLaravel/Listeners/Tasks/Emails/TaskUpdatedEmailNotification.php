<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Tasks\Emails;

use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Tasks\UpdateTaskEvent;
use Webaccess\ProjectSquareLaravel\Models\Task;

class TaskUpdatedEmailNotification
{
    public function handle(UpdateTaskEvent $event)
    {
        if (isset($event->taskID) && $event->taskID) {
            if ($task = Task::where('id', '=', $event->taskID)->with('project', 'project.client', 'allocated_user')->first()) {

                if (isset($task->allocated_user)) {
                    $setting = app()->make('SettingManager')->getSettingByKeyAndUser('EMAIL_NOTIFICATION_TASK_UPDATED', $task->allocated_user->id);

                    if ($setting && boolval($setting->value) === true) {
                        $email = $task->allocated_user->email;

                        Mail::send('projectsquare::emails.task_updated', array('task' => $task), function ($message) use ($email, $task) {
                            $message->to($email)
                                ->subject('[projectsquare] Modification de la tâche : ' . $task->title);
                        });
                    }
                }
            }
        }
    }
}
