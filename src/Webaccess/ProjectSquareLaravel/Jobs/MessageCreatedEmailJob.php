<?php

namespace Webaccess\ProjectSquareLaravel\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Messages\CreateMessageEvent;
use Webaccess\ProjectSquareLaravel\Models\Message;

class MessageCreatedEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;

    /**
     * Create a new job instance.
     *
     * @param CreateMessageEvent $event
     */
    public function __construct(CreateMessageEvent $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($m = Message::where('id', '=', $this->event->message->id)->with('user', 'conversation', 'conversation.project')->first()) {
            $authorUserID = $m->user->id;

            $clientUsers = app()->make('UserManager')->getUsersByClient($m->conversation->project->client->id);
            $projectUsers = app()->make('UserManager')->getUsersByProject($m->conversation->project->id);

            foreach ($clientUsers as $user) {
                $setting = app()->make('SettingManager')->getSettingByKeyAndUser('EMAIL_NOTIFICATION_MESSAGE_CREATED', $user->id);

                if ($user->id != $authorUserID && $setting && boolval($setting->value) === true) {
                    $email = $user->email;

                    Mail::send('projectsquare::emails.message_created', array('m' => $m, 'user' => $user), function ($message) use ($email) {
                        $message->to($email)
                            ->subject('[projectsquare] Un nouveau message vient d\'être envoyé sur la plateforme');
                    });
                }
            }

            foreach ($projectUsers as $user) {
                $setting = app()->make('SettingManager')->getSettingByKeyAndUser('EMAIL_NOTIFICATION_MESSAGE_CREATED', $user->id);

                if ($user->id != $authorUserID && $setting && boolval($setting->value) === true) {
                    $email = $user->email;

                    Mail::send('projectsquare::emails.message_created', array('m' => $m, 'user' => $user), function ($message) use ($email) {
                        $message->to($email)
                            ->subject('[projectsquare] Un nouveau message vient d\'être envoyé sur la plateforme');
                    });
                }
            }
        }
    }
}
