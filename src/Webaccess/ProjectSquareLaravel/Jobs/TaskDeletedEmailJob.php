<?php

namespace Webaccess\ProjectSquareLaravel\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Tasks\DeleteTaskEvent;
use Webaccess\ProjectSquareLaravel\Models\Message;
use Webaccess\ProjectSquareLaravel\Models\Task;

class TaskDeletedEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;

    /**
     * Create a new job instance.
     *
     * @param DeleteTaskEvent $event
     */
    public function __construct(DeleteTaskEvent $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (isset($this->event->task->id) && $this->event->task->id) {
            if ($task = Task::where('id', '=', $this->event->task->id)->with('project', 'project.client', 'allocated_user')->first()) {

                if (isset($task->allocated_user)) {
                    $setting = app()->make('SettingManager')->getSettingByKeyAndUser('EMAIL_NOTIFICATION_TASK_DELETED', $task->allocated_user->id);

                    if ($setting && boolval($setting->value) === true) {
                        $email = $task->allocated_user->email;

                        Mail::send('projectsquare::emails.task_deleted', array('task' => $task), function ($message) use ($email, $task) {
                            $message->to($email)
                                ->subject('[projectsquare] Suppression de la tâche : ' . $task->title);
                        });
                    }
                }
            }
        }
    }
}
