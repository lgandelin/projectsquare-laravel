<?php

namespace Webaccess\ProjectSquareLaravel\Commands;

use Illuminate\Console\Command;
use Webaccess\ProjectSquare\Context;
use Webaccess\ProjectSquare\Events\Alerts\AlertWebsiteLoadingTimeEvent;
use Webaccess\ProjectSquare\Events\Events;

class AlertLoadingTimeCommand extends Command
{
    protected $signature = 'projectsquare:alert-loading-time';

    protected $description = 'Alerter un administrateur qu\'un site met du temps à charger';

    public function handle()
    {
        foreach (app()->make('ProjectManager')->getProjects() as $project) {
            if ($request = app()->make('RequestManager')->getLastRequestByProject($project->id)) {

                $settingAcceptableLoadingTime = app()->make('SettingManager')->getSettingByKeyAndProject('ACCEPTABLE_LOADING_TIME', $project->id);
                $settingAlertLoadingTimeEmail = app()->make('SettingManager')->getSettingByKeyAndProject('ALERT_LOADING_TIME_EMAIL', $project->id);

                if ($settingAcceptableLoadingTime && $settingAlertLoadingTimeEmail) {
                    $loadingTime = $settingAcceptableLoadingTime->value;
                    $email = $settingAlertLoadingTimeEmail->value;

                    if ($request->loading_time > (float) $loadingTime) {
                        app()->make('AlertManager')->createAlert(
                            'WEBSITE_LOADING_TIME',
                            [
                                'loading_time_setting' => $loadingTime,
                                'loading_time' => $request->loading_time,
                            ],
                            $project->id
                        );

                        Context::get('event_dispatcher')->dispatch(
                            Events::ALERT_LOADING_TIME,
                            new AlertWebsiteLoadingTimeEvent($request, $email, $loadingTime)
                        );
                    }
                }
            }
        }
    }
}
