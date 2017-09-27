<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Tickets\Emails;

use Webaccess\ProjectSquare\Events\Tickets\CreateTicketEvent;
use Webaccess\ProjectSquareLaravel\Jobs\Tickets\TicketCreatedEmailJob;

class TicketCreatedEmailNotification
{
    public function handle(CreateTicketEvent $event)
    {
        TicketCreatedEmailJob::dispatch($event)->onQueue('emails');
    }
}
