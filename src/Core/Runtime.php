<?php


namespace Pf\Core;


class Runtime
{


    public static function register()
    {

        $app = \Pf\App::getInstance();
        $app->env_cli = 'cli';
        $app->env_api = 'api';
        $app->env_web = 'web';
        $app->request->env = php_sapi_name() == $app->env_cli
            ? $app->env_cli
            : (
                !empty($app->request->server('REQUEST_URI')) && substr($app->request->server('REQUEST_URI'), 0,5) == '/' . $app->env_api . '/'
                    ? $app->env_api
                    : $app->env_web
            )
        ;

    }



}
