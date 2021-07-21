<?php


namespace Pf\Core;


class Env
{


    public static function register($app)
    {

        if(file_exists($app->path_file_env))
        {
            require_once $app->path_file_env;
        }

    }


}
