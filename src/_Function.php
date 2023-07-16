<?php

use Junyang7\PhpCommon\_Exception;

function C($name = "", $default = null)
{

    $conf = $_ENV["_pf"]["conf"];
    if (empty($name)) {
        return $conf;
    }
    foreach (explode(".", $name) as $key) {
        if (!isset($conf[$key])) {
            return $default;
        }
        $conf = $conf[$key];
    }
    return $conf;

}

function I($ok, $error = null, $data = null)
{

    if ($ok) {
        return;
    }
    if (is_array($error) && 2 === count($error)) {
        list($code, $message) = $error;
    } else {
        $code = -1;
        $message = is_scalar($error) ? $error : "";
    }
    throw new _Exception($message, $data, $code);

}
