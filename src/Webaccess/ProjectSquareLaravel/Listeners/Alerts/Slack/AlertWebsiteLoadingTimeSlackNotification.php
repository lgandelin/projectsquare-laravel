<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Alerts\Slack;

use Webaccess\ProjectSquare\Events\Alerts\AlertWebsiteLoadingTimeEvent;
use Webaccess\ProjectSquareLaravel\Tools\SlackTool;

class AlertWebsiteLoadingTimeSlackNotification
{
    public function handle(AlertWebsiteLoadingTimeEvent $event)
    {
        $request = $event->request;
        $loadingTime = $event->loadingTime;

        $lines = [
            'Le site internet a mis plus de '.$loadingTime.'s a charger  ('.$request->loading_time.'s)',
        ];

        $settingSlackChannel = app()->make('SettingManager')->getSettingByKeyAndProject('SLACK_CHANNEL', $request->project_id);

        SlackTool::send(
            'Alerte monitoring',
            implode("\n", $lines),
            null,
            null,
            ($settingSlackChannel) ? $settingSlackChannel->value : '',
            '#ff0000'
        );
    }
}
