<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Tasks\Emails;

use Webaccess\ProjectSquare\Events\Tasks\UpdateTaskEvent;
use Webaccess\ProjectSquareLaravel\Jobs\Tasks\TaskUpdatedEmailJob;

class TaskUpdatedEmailNotification
{
    public function handle(UpdateTaskEvent $event)
    {
        TaskUpdatedEmailJob::dispatch($event)->onQueue('emails');
    }
}
