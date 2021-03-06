<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Alerts\Emails;

use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Alerts\AlertWebsiteLoadingTimeEvent;

class AlertWebsiteLoadingTimeEmailNotification
{
    public function handle(AlertWebsiteLoadingTimeEvent $event)
    {
        $request = $event->request;
        $email = $event->email;
        $loadingTime = $event->loadingTime;
        $project = app()->make('ProjectManager')->getProject($event->request->project_id);

        Mail::send('projectsquare::emails.alert_loading_time', ['request' => $request, 'project' => $project], function ($m) use ($request, $project, $email, $loadingTime) {
            $m->to($email)->subject('['.$project->clientName.' - ' . $project->name . '] Le site internet a mis plus de '.$loadingTime.'s a charger ('.$request->loading_time.'s)');
        });
    }
}
