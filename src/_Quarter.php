<?php

namespace Junyang7\PhpCommon;

class _Quarter
{

    public static function getByTimestamp($timestamp)
    {

        $m = @date("m", $timestamp);
        if (false === $m) {
            throw new \Exception("date格式化操作失败");
        }
        switch ($m) {
            case 1:
            case 2:
            case 3:
                return 1;
            case 4:
            case 5:
            case 6:
                return 2;
            case 7:
            case 8:
            case 9:
                return 3;
            case 10:
            case 11:
            case 12:
                return 4;
            default:
                return 0;
        }

    }

    public static function get()
    {

        return self::getByTimestamp(time());

    }

}
