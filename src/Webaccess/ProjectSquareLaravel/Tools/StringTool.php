<?php

namespace Webaccess\ProjectSquareLaravel\Tools;

use Illuminate\Support\Str;

class StringTool
{
    public static function slugify($string)
    {
        $extension = FileTool::extractExtension($string);
        $string = ($extension) ? FileTool::removeExtension($string, $extension) : $string;
        $string = Str::slug($string);
        $string = ($extension) ? $string.'.'.$extension : $string;

        return $string;
    }

    public static function formatNumber($number)
    {
        return $number ? str_replace(',', '.', $number) : 0;
    }

    public static function getShortID($uuid)
    {
        return substr($uuid, 0, 8);
    }
}
