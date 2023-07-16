<?php

namespace Junyang7\PhpCommon;

class _Array
{

    public static function group($row_list, $key, $associative = false)
    {

        $res = [];
        foreach ($row_list as $row) {
            $associative ? $res[$row[$key]] = $row : $res[$row[$key]][] = $row;
        }
        return $res;

    }

    public static function merge($a, $b)
    {

        foreach ($b as $k => $v) {
            if (!isset($a[$k]) || is_scalar($a[$k]) || is_scalar($v)) {
                $a[$k] = $v;
                continue;
            }
            $a[$k] = self::merge($a[$k], $v);
        }
        return $a;

    }

}
