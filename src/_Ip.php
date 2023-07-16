<?php

namespace Junyang7\PhpCommon;

class _Ip
{

    public static function get()
    {

        $ip = "";
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && "127.0.0.1" != $_SERVER['"HTTP_X_FORWARDED_FOR']) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            $pos = strpos($ip, ",");
            if ($pos > 0) {
                $ip = substr($ip, 0, $pos);
            }
        } elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER["REMOTE_ADDR"])) {
            $ip = $_SERVER["REMOTE_ADDR"];
        }
        if ("" != $ip) {
            $pos = strpos($ip, ":");
            if ($pos > 0) {
                $ip = substr($ip, 0, $pos);
            }
            $ip = trim($ip);
        } else {
            $ip = "127.0.0.1";
        }
        return $ip;

    }

}
