<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Messages\Emails;

use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Messages\CreateMessageEvent;
use Webaccess\ProjectSquareLaravel\Models\Message;

class MessageCreatedEmailNotification
{
    public function handle(CreateMessageEvent $event)
    {
        if ($m = Message::where('id', '=', $event->message->id)->with('user', 'conversation', 'conversation.project')->first()) {

            $clientUsers = app()->make('UserManager')->getUsersByClient($m->conversation->project->client->id);
            $projectUsers = app()->make('UserManager')->getUsersByProject($m->conversation->project->id);

            foreach ($clientUsers as $user) {
                $setting = app()->make('SettingManager')->getSettingByKeyAndUser('EMAIL_NOTIFICATION_MESSAGE_CREATED', $user->id);

                if ($setting && boolval($setting->value) === true) {
                    $email = $user->email;

                    Mail::send('projectsquare::emails.message_created', array('m' => $m, 'user' => $user), function ($message) use ($email) {
                        $message->to($email)
                            ->from('no-reply@projectsquare.io')
                            ->subject('[projectsquare] Un nouveau message vient d\'être envoyé sur la plateforme');
                    });
                }
            }

            foreach ($projectUsers as $user) {
                $setting = app()->make('SettingManager')->getSettingByKeyAndUser('EMAIL_NOTIFICATION_MESSAGE_CREATED', $user->id);

                if ($setting && boolval($setting->value) === true) {
                    $email = $user->email;

                    Mail::send('projectsquare::emails.message_created', array('m' => $m, 'user' => $user), function ($message) use ($email) {
                        $message->to($email)
                            ->from('no-reply@projectsquare.io')
                            ->subject('[projectsquare] Un nouveau message vient d\'être envoyé sur la plateforme');
                    });
                }
            }
        }
    }
}
