<?php

use Junyang7\PhpCommon\_Conf;
use Junyang7\PhpCommon\_Exception;

if (!function_exists("C")) {
    function C($name, $default = null)
    {
        if (empty($name)) {
            return $default;
        }
        $name_list = explode(".", $name);
        $conf = _Conf::$conf;
        foreach ($name_list as $name) {
            if (!isset($conf[$name])) {
                return $default;
            }
            $conf = $conf[$name];
        }
        return $conf;
    }
}

if (!function_exists("I")) {
    function I($ok, $exception, $data_user = [], $data_system = [])
    {
        if (!$ok) {
            throw new _Exception($exception, $data_user, $data_system);
        }
    }
}
