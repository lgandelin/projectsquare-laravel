<?php

namespace Webaccess\ProjectSquareLaravel\Listeners;

use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Tasks\CreateTaskEvent;
use Webaccess\ProjectSquareLaravel\Models\Task;

class TaskCreatedEmailNotification
{
    public function handle(CreateTaskEvent $event)
    {
        if ($task = Task::find($event->taskID)) {

            $user = app()->make('UserManager')->getUser($task->allocated_user_id);
            $email = $user->email;

            Mail::send('projectsquare::emails.task_created', array('task' => $task, 'user' => $user), function ($message) use ($email, $task) {
                $message->to($email)
                    ->from('no-reply@projectsquare.fr')
                    ->subject('[projectsquare] Une nouvelle tâche vous a été assignée');
            });
        }
    }
}
