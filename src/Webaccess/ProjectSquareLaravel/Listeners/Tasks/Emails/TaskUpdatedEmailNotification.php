<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Tasks\Emails;

use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Tasks\UpdateTaskEvent;
use Webaccess\ProjectSquareLaravel\Jobs\TaskUpdatedEmailJob;
use Webaccess\ProjectSquareLaravel\Models\Task;

class TaskUpdatedEmailNotification
{
    public function handle(UpdateTaskEvent $event)
    {
        TaskUpdatedEmailJob::dispatch($event)->onQueue('emails');
    }
}
