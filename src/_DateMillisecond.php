<?php

namespace Junyang7\PhpCommon;

class _DateMillisecond
{

    public static function get(): string
    {

        return self::getByMilliTimestamp(intval(microtime(true) * 1000));

    }

    public static function getByMilliTimestamp(int $milli_timestamp): string
    {

        $t = substr($milli_timestamp, 0, 10);
        $m = substr($milli_timestamp, 10, 3);
        return _Datetime::getByTimestamp($t) . "." . $m;

    }

}
