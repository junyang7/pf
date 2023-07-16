<?php

namespace Junyang7\PhpCommon;

class _Aes
{

    public static function encode($data, $k32, $i16)
    {

        $res = openssl_encrypt($data, "AES-256-CBC", $k32, OPENSSL_RAW_DATA, $i16);
        if (false === $res) {
            throw new \Exception("openssl_encrypt操作失败");
        }
        return _Base64::encode($res);

    }

    public static function decode($data, $k32, $i16)
    {

        $res = openssl_decrypt(_Base64::decode($data), "AES-256-CBC", $k32, OPENSSL_RAW_DATA, $i16);
        if (false === $res) {
            throw new \Exception("openssl_decrypt操作失败");
        }
        return $res;

    }

    public static function get()
    {

        $res = [];
        $res["k32"] = _RandomString::get(32, "0123456789qwertyuiopasdfghjklzxcvbnm");
        $res["i16"] = _RandomString::get(16, "0123456789qwertyuiopasdfghjklzxcvbnm");
        return $res;

    }

}
