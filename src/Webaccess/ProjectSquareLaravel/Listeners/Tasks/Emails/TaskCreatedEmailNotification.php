<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Tasks\Emails;

use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Tasks\CreateTaskEvent;
use Webaccess\ProjectSquareLaravel\Models\Task;

class TaskCreatedEmailNotification
{
    public function handle(CreateTaskEvent $event)
    {
        if (isset($event->taskID) && $event->taskID) {
            if ($task = Task::where('id', '=', $event->taskID)->with('allocated_user')->first()) {

                if (isset($task->allocated_user)) {
                    $setting = app()->make('SettingManager')->getSettingByKeyAndUser('EMAIL_NOTIFICATION_TASK_CREATED', $task->allocated_user->id);

                    if ($setting && boolval($setting->value) === true) {
                        $email = $task->allocated_user->email;

                        Mail::send('projectsquare::emails.task_created', array('task' => $task), function ($message) use ($email, $task) {
                            $message->to($email)
                                ->subject('[projectsquare] Une nouvelle tâche vous a été assignée');
                        });
                    }
                }
            }
        }
    }
}
