<?php


namespace Pf\Core;


class Error
{


    public static function register()
    {

        set_error_handler([self::class, 'handleError']);

    }


    public static function handleError($code, $message)
    {

        throw new \Exception($message, $code);

    }


}
