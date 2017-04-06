<?php

namespace Webaccess\ProjectSquareLaravel\Tools;

class DurationConverter
{
    public static function convertToCalendarDuration($estimatedTimeDays)
    {
        $days = floor($estimatedTimeDays);
        $duration = "";
        if ($days > 0) {
            $duration .= $days.'.';
        }
        $hours = ($estimatedTimeDays-$days)*8;
        $duration .= $hours.':00';

        //Default duration
        if ($duration == '0:00') $duration = '4:00';

        return $duration;
    }
}