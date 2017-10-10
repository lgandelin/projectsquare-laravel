<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Messages\Emails;

use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Messages\CreateMessageEvent;
use Webaccess\ProjectSquareLaravel\Jobs\MessageCreatedEmailJob;
use Webaccess\ProjectSquareLaravel\Models\Message;

class MessageCreatedEmailNotification
{
    public function handle(CreateMessageEvent $event)
    {
        MessageCreatedEmailJob::dispatch($event)->onQueue('emails');
    }
}
