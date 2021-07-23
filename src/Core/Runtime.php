<?php


namespace Pf\Core;


class Runtime
{


    public static function register()
    {

        error_reporting(-1);
        ini_set('display_errors', 'On');

        $app = \Pf\App::getInstance();
        $app->path_dir_base = dirname(getcwd());
        $app->env_cli = 'cli';
        $app->env_api = 'api';
        $app->env_web = 'web';
        $app->request = \Pf\Core\Request::getInstance();
        $app->response = \Pf\Core\Response::getInstance();

        if(php_sapi_name() == $app->env_cli)
        {
            $app->request->env = $app->env_cli;
        }
        else if(($uri_length = strlen($app->request->server('REQUEST_URI'))) > 0 && ($uri_length >= 5 && substr($app->request->server('REQUEST_URI'), 0,5) == '/' . $app->env_api . '/' || $uri_length == 4 && substr($app->request->server('REQUEST_URI'), 0,4) == '/' . $app->env_api))
        {
            $app->request->env = $app->env_api;
        }
        else
        {
            $app->request->env = $app->env_web;
        }

    }


}
