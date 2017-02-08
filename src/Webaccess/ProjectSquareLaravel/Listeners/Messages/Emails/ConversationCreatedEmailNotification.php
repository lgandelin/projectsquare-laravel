<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Messages\Emails;

use Webaccess\ProjectSquare\Events\Messages\CreateConversationEvent;

class ConversationCreatedEmailNotification
{
    public function handle(CreateConversationEvent $event)
    {
    }
}
