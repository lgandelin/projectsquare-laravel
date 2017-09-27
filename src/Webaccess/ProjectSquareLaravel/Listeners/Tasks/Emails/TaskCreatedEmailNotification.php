<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Tasks\Emails;

use Webaccess\ProjectSquare\Events\Tasks\CreateTaskEvent;
use Webaccess\ProjectSquareLaravel\Jobs\Tasks\TaskCreatedEmailJob;

class TaskCreatedEmailNotification
{
    public function handle(CreateTaskEvent $event)
    {
        TaskCreatedEmailJob::dispatch($event)->onQueue('emails');
    }
}
