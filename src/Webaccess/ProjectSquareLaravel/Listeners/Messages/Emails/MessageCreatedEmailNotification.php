<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Messages\Emails;

use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Messages\CreateMessageEvent;
use Webaccess\ProjectSquareLaravel\Models\Message;

class MessageCreatedEmailNotification
{
    public function handle(CreateMessageEvent $event)
    {
        if ($m = Message::find($event->message->id)) {
            $user = $m->user;
            $email = $user->email;

            Mail::send('projectsquare::emails.message_created', array('m' => $m, 'user' => $user), function ($message) use ($email) {
                $message->to()
                    ->from($email)
                    ->subject('[projectsquare] Un nouveau message vient d\'être envoyé sur la plateforme');
            });
        }
    }
}
