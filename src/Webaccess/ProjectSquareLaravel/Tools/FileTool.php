<?php

namespace Webaccess\ProjectSquareLaravel\Tools;

class FileTool
{
    public static function convertFileSize($bytes)
    {
        if ($bytes > 0) {
            $unit = intval(log($bytes, 1024));
            $units = array('B', 'KB', 'MB', 'GB');

            if (array_key_exists($unit, $units) === true) {
                return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
            }
        }

        return $bytes;
    }

    public static function extractExtension($string)
    {
        return pathinfo($string, PATHINFO_EXTENSION);
    }

    public static function removeExtension($string, $extension)
    {
        return preg_replace('/'.$extension.'/', '', $string);
    }
}
