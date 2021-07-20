<?php


namespace Pf\Core;


class Runtime
{


    public static function register($base_dir)
    {

        error_reporting(-1);
        ini_set('display_errors', 'On');
        define('_PF_DIR', $base_dir);
        define('_PF_ENV_CLI', 'cli');
        define('_PF_ENV_API', 'api');
        define('_PF_ENV_WEB', 'web');
        define('_PF_ENV', php_sapi_name() == _PF_ENV_CLI ? _PF_ENV_CLI : (isset($_SERVER['REQUEST_URI']) && substr($_SERVER['REQUEST_URI'], 0,5) == '/api/' ? _PF_ENV_API : _PF_ENV_WEB));

    }


}
