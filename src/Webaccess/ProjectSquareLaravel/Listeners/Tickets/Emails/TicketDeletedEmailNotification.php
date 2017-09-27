<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Tickets\Emails;

use Webaccess\ProjectSquare\Events\Tickets\DeleteTicketEvent;
use Webaccess\ProjectSquareLaravel\Jobs\Tickets\TicketDeletedEmailJob;

class TicketDeletedEmailNotification
{
    public function handle(DeleteTicketEvent $event)
    {
        TicketDeletedEmailJob::dispatch($event)->onQueue('emails');
    }
}
