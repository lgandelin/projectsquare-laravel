<?php

namespace Webaccess\ProjectSquareLaravel\Tools;

class PingTool
{
    public static function exec($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:19.0) Gecko/20100101 Firefox/19.0');
        curl_exec($ch);

        if (!curl_errno($ch)) {
            $info = curl_getinfo($ch);

            return array($info['http_code'], $info['total_time']);
        }

        curl_close($ch);

        return false;
    }
}
