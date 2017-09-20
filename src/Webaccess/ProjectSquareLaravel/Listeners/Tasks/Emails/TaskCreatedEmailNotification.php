<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Tasks\Emails;

use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Tasks\CreateTaskEvent;
use Webaccess\ProjectSquareLaravel\Jobs\TaskCreatedEmailJob;
use Webaccess\ProjectSquareLaravel\Models\Task;

class TaskCreatedEmailNotification
{
    public function handle(CreateTaskEvent $event)
    {
        TaskCreatedEmailJob::dispatch($event)->onQueue('emails');
    }
}
