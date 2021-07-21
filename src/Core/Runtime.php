<?php


namespace Pf\Core;


class Runtime
{


    public static function register($request)
    {

        error_reporting(-1);
        $request->env_cli = 'cli';
        $request->env_api = 'api';
        $request->env_web = 'web';
        $request->env = php_sapi_name() == $request->env_cli
            ? $request->env_cli
            : (
                !empty($request->server('REQUEST_URI')) && substr($request->server('REQUEST_URI'), 0,5) == '/' . $request->env_api . '/'
                    ? $request->env_api
                    : $request->env_web
            )
        ;

    }



}
