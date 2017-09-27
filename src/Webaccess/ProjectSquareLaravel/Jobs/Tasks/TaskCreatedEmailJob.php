<?php

namespace Webaccess\ProjectSquareLaravel\Jobs\Tasks;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Tasks\CreateTaskEvent;
use Webaccess\ProjectSquareLaravel\Models\Task;

class TaskCreatedEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;

    /**
     * Create a new job instance.
     *
     * @param CreateTaskEvent $event
     */
    public function __construct(CreateTaskEvent $event)
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
        if (isset($this->event->taskID) && $this->event->taskID) {
            if ($task = Task::where('id', '=', $this->event->taskID)->with('allocated_user')->first()) {

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
