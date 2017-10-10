<?php

namespace Webaccess\ProjectSquareLaravel\Jobs\Tasks;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Tasks\UpdateTaskEvent;
use Webaccess\ProjectSquareLaravel\Models\Task;

class TaskUpdatedEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;

    /**
     * Create a new job instance.
     *
     * @param UpdateTaskEvent $event
     */
    public function __construct(UpdateTaskEvent $event)
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
            if ($task = Task::where('id', '=', $this->event->taskID)->with('project', 'project.client', 'allocated_user')->first()) {

                if (isset($task->allocated_user) && $this->event->requesterUserID != $task->allocated_user->id) {
                    $setting = app()->make('SettingManager')->getSettingByKeyAndUser('EMAIL_NOTIFICATION_TASK_UPDATED', $task->allocated_user->id);

                    if ($setting && boolval($setting->value) === true) {
                        $email = $task->allocated_user->email;

                        $subject = ($task->allocated_user->id != $this->event->oldAllocatedUserID) ? 'Attribution d\'une tâche' : 'Modification de la tâche';

                        Mail::send('projectsquare::emails.task_updated', array('task' => $task), function ($message) use ($email, $task, $subject) {
                            $message->to($email)
                                ->subject('[projectsquare] ' . $subject . ' : ' . $task->title);
                        });
                    }
                }
            }
        }
    }
}
