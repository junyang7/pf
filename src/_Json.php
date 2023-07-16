<?php

namespace Junyang7\PhpCommon;

class _Json
{

    public static function encode($data, $flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES, $depth = 512)
    {

        $res = json_encode($data, $flags, $depth);
        if (false === $res) {
            throw new \Exception("json_encode操作失败");
        }
        return $res;

    }

    public static function decode($data, $associative = true, $depth = 512, $flags = JSON_BIGINT_AS_STRING)
    {

        $res = json_decode($data, $associative, $depth, $flags);
        if (is_null($res)) {
            throw new \Exception("json_decode操作失败");
        }
        return $res;

    }

}
