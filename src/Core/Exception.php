<?php


namespace Pf\Core;


class Exception
{


    public static function register()
    {

        set_exception_handler([self::class, 'handleException']);

    }


    public static function handleException($exception)
    {

        Render::exception($exception);

    }


}
