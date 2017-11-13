<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Messages\Emails;

use Webaccess\ProjectSquare\Events\Messages\CreateMessageEvent;
use Webaccess\ProjectSquareLaravel\Jobs\Messages\MessageCreatedEmailJob;

class MessageCreatedEmailNotification
{
    public function handle(CreateMessageEvent $event)
    {
        MessageCreatedEmailJob::dispatch($event)->onQueue('emails');
    }
}
