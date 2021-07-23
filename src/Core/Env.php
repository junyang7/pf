<?php


namespace Pf\Core;


class Env
{


    public static function register()
    {

        $app = \Pf\App::getInstance();
        $app->path_file_env = $app->path_dir_base . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR . 'env.php';

        if(file_exists($app->path_file_env))
        {
            require_once $app->path_file_env;
        }

    }


}
