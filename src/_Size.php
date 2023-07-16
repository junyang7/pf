<?php

namespace Junyang7\PhpCommon;

class _Size
{

    private const BASE = 1024;
    private const UNIT = ["B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB", "BB", "NB", "DB", "CB",];
    private const UNIT_MAX_INDEX = 12;

    public static function get($size, $precision = 4)
    {

        $i = floor(log($size, self::BASE));
        if ($i > self::UNIT_MAX_INDEX) {
            throw new \Exception("超出最大计量单位");
        }
        return round($size / pow(self::BASE, $i), $precision) . self::UNIT[$i];

    }

}
