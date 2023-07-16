<?php

namespace Junyang7\PhpCommon;

class _NanoId
{

    public static function get($size = 32, $alphabet = "_-0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ")
    {

        $len = strlen($alphabet);
        $mask = (2 << (int)(log($len - 1) / M_LN2)) - 1;
        $step = (int)ceil(1.6 * $mask * $size / $len);
        $id = "";
        while (true) {
            $byte_list = unpack("C*", \random_bytes($step));
            foreach ($byte_list as $byte) {
                $byte &= $mask;
                $id .= $alphabet[$byte];
                if (strlen($id) === $size) {
                    return $id;
                }
            }
        }

    }

}
