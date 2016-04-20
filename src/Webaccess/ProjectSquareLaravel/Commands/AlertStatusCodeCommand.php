<?php

namespace Webaccess\ProjectSquareLaravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Event;
use Webaccess\ProjectSquareLaravel\Events\AlertWebsiteStatusCodeEvent;

class AlertStatusCodeCommand extends Command
{
    protected $signature = 'projectsquare:alert-status-code';

    protected $description = 'Alerter un administrateur qu\'un site a retourné une erreur';

    public function handle()
    {
        foreach (app()->make('ProjectManager')->getProjects() as $project) {
            $request = app()->make('RequestManager')->getLastRequestByProject($project->id);

            $settingAlertEmail = app()->make('SettingManager')->getSettingByKeyAndProject('ALERT_LOADING_TIME_EMAIL', $project->id);
            $statusCode = $request->status_code;

            if ($settingAlertEmail) {
                $email = $settingAlertEmail->value;

                if ($this->isStatusCodeAnError($statusCode)) {
                    app()->make('AlertManager')->createAlert(
                        'WEBSITE_STATUS_CODE',
                        [
                            'status_code' => $statusCode,
                        ],
                        $project->id
                    );

                    Event::fire(new AlertWebsiteStatusCodeEvent($request, $email, $statusCode));
                }
            }
        }
    }

    protected function isStatusCodeAnError($statusCode)
    {
        return substr($statusCode, 0, 1) == '4' || substr($statusCode, 0, 1) == '5';
    }
}