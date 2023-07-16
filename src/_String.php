<?php

namespace Junyang7\PhpCommon;

class _String
{

    public static function hasPrefix($string, $prefix)
    {

        return substr($string, 0, strlen($prefix)) == $prefix;

    }

    public static function hasSuffix($string, $suffix)
    {

        return substr($string, -strlen($suffix)) == $suffix;

    }

}
