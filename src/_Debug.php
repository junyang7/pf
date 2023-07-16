<?php

namespace Junyang7\PhpCommon;

class _Debug
{

    public static function echo($data)
    {

        if (!is_scalar($data)) {
            $data = _Json::encode($data);
        }
        echo _Datetime::get() . "\t" . $data . PHP_EOL;

    }

}
