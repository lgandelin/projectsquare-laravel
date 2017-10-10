<?php

namespace Webaccess\ProjectSquareLaravel\Tools;

class PlanningTool
{
    public static function convertHoursToFullCalendar($hours)
    {
        $minutesString = 0;
        $hoursString = 0;

        //Default duration
        if (!$hours) {
            $hoursString = 2;
        } else {
            $hoursString = floor($hours);
            $minutesString = 60 * ($hours - $hoursString);
        }

        return $hoursString . ':' . $minutesString;
    }

    public static function convertDaysToFullCalendar($days)
    {
        $daysString = 0;
        $hoursString = 0;

        //Default duration
        if (!$days) {
            $hoursString = 4;
        } else {
            $daysString = floor($days);
            $hoursString = 8 * ($days - $daysString);
        }

        return $daysString . '.' . $hoursString . ':00';
    }
}