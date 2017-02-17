<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Tasks\Emails;

use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Tasks\DeleteTaskEvent;
use Webaccess\ProjectSquareLaravel\Models\Task;

class TaskDeletedEmailNotification
{
    public function handle(DeleteTaskEvent $event)
    {
        if (isset($event->task->id) && $event->task->id) {
            if ($task = Task::where('id', '=', $event->task->id)->with('project', 'project.client', 'allocated_user')->first()) {

                if (isset($task->allocated_user)) {
                    $setting = app()->make('SettingManager')->getSettingByKeyAndUser('EMAIL_NOTIFICATION_TASK_DELETED', $task->allocated_user->id);

                    if ($setting && boolval($setting->value) === true) {
                        $email = $task->allocated_user->email;

                        Mail::send('projectsquare::emails.task_deleted', array('task' => $task), function ($message) use ($email, $task) {
                            $message->to($email)
                                ->from('no-reply@projectsquare.io')
                                ->subject('[projectsquare] Suppression de la tâche : ' . $task->title);
                        });
                    }
                }
            }
        }
    }
}
