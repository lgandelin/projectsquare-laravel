<?php

namespace Webaccess\ProjectSquareLaravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Event;
use Webaccess\ProjectSquareLaravel\Events\AlertWebsiteLoadingTimeEvent;

class AlertLoadingTimeCommand extends Command
{
    protected $signature = 'projectsquare:alert-loading-time';

    protected $description = 'Alerter un administrateur qu\'un site met du temps à charger';

    public function handle()
    {
        foreach (app()->make('ProjectManager')->getProjects() as $project) {
            $request = app()->make('RequestManager')->getLastRequestByProject($project->id);

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

                    Event::fire(new AlertWebsiteLoadingTimeEvent($request, $email, $loadingTime));
                }
            }
        }
    }
}
