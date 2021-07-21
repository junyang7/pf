<?php


namespace Pf\Core;


class Env
{


    public static function register($request)
    {

        if(file_exists($request->path_file_env))
        {
            require_once $request->path_file_env;
        }

    }


}
