<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Tickets\Emails;

use Webaccess\ProjectSquare\Events\Tickets\UpdateTicketEvent;
use Webaccess\ProjectSquareLaravel\Jobs\Tickets\TicketUpdatedEmailJob;

class TicketUpdatedEmailNotification
{
    public function handle(UpdateTicketEvent $event)
    {
        TicketUpdatedEmailJob::dispatch($event)->onQueue('emails');
    }
}
