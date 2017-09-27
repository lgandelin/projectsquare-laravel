<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Tasks\Emails;

use Webaccess\ProjectSquare\Events\Tasks\DeleteTaskEvent;
use Webaccess\ProjectSquareLaravel\Jobs\Tasks\TaskDeletedEmailJob;

class TaskDeletedEmailNotification
{
    public function handle(DeleteTaskEvent $event)
    {
        TaskDeletedEmailJob::dispatch($event)->onQueue('emails');
    }
}
