<?php

namespace Junyang7\PhpCommon;

class _Url
{

    public static function get($protocol, $domain, $port = 0, $uri = "", $parameter = [])
    {

        $res = $protocol . "://" . $domain;
        if (!($port == 0 || 80 == $port && "http" == $protocol || 443 == $port && "https" == $protocol)) {
            $res .= ":" . $port;
        }
        if (!empty($uri)) {
            $res .= "/" . trim($uri, "/");
        }
        if (!empty($parameter)) {
            $res .= "?" . http_build_query($parameter);
        }
        return $res;

    }

}
