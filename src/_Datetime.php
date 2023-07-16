<?php

namespace Junyang7\PhpCommon;

class _Datetime
{

    public static function getByFormatAndTimestamp($format, $timestamp)
    {

        $res = @date($format, $timestamp);
        if (false === $res) {
            throw new \Exception("date格式化操作失败");
        }
        return $res;

    }

    public static function getByFormat($format)
    {

        return self::getByFormatAndTimestamp($format, time());

    }

    public static function getByTimestamp($timestamp)
    {

        return self::getByFormatAndTimestamp("Y-m-d H:i:s", $timestamp);

    }

    public static function get()
    {

        return self::getByFormatAndTimestamp("Y-m-d H:i:s", time());

    }

}
