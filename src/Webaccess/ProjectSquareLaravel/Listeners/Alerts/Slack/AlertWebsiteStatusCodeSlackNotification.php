<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Alerts\Slack;

use Webaccess\ProjectSquare\Events\Alerts\AlertWebsiteStatusCodeEvent;
use Webaccess\ProjectSquareLaravel\Tools\SlackTool;

class AlertWebsiteStatusCodeSlackNotification
{
    public function handle(AlertWebsiteStatusCodeEvent $event)
    {
        $request = $event->request;
        $statusCode = $event->statusCode;

        $lines = [
            'Le site internet a retourné une erreur (code : '.$statusCode.')',
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
