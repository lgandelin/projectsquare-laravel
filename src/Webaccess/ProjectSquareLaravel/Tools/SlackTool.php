<?php

namespace Webaccess\ProjectSquareLaravel\Tools;

class SlackTool
{
    public static function send($title, $description, $authorName, $link, $channel, $color)
    {
        $payload = [
            'channel' => $channel,
            'text' => null,
            'attachments' => [
                [
                    'color' => $color,
                    'title' => $title,
                    'author_name' => $authorName,
                    'title_link' => $link,
                    'text' => $description,
                    'mrkdwn_in' => ['fields', 'text'],
                ],
            ],
        ];

        ob_start();
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, env('SLACK_URL'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $result = curl_exec($ch);
        curl_close($ch);
        ob_end_clean();
    }
}
