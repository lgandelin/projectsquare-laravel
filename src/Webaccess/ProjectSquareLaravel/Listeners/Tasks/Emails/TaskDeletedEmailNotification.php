<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Tasks\Emails;

use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Tasks\DeleteTaskEvent;
use Webaccess\ProjectSquareLaravel\Jobs\TaskDeletedEmailJob;
use Webaccess\ProjectSquareLaravel\Models\Task;

class TaskDeletedEmailNotification
{
    public function handle(DeleteTaskEvent $event)
    {
        TaskDeletedEmailJob::dispatch($event)->onQueue('emails');
    }
}
