<?php


namespace Pf\Core;


class Shutdown
{


    public static function register()
    {

        register_shutdown_function([self::class, 'handleShutdown']);

    }


    public static function handleShutdown()
    {

        if($error = error_get_last())
        {
            throw new \Exception($error['message'], $error['type']);
        }

    }


}
