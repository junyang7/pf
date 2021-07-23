<?php


namespace Pf;


class App
{


    private static $instance;
    private function __construct()
    {

    }
    private function __clone()
    {

    }
    public static function getInstance()
    {

        if(!self::$instance instanceof self)
        {
            self::$instance = new self();
        }

        return self::$instance;

    }


    public $path_dir_base;
    public $request;
    public $response;
    public $env_cli;
    public $env_api;
    public $env_web;
    public $path_file_env;
    public $conf_list;
    public $path_dir_common;
    public $extend_conf;
    public $length_extend_conf;
    public $path_dir_env;
    public $path_dir_route;
    public $extend_route;
    public $length_extend_route;
    public $support_method_list;
    public $controller_namespace;
    public $uri_prefix;
    public $rule_pattern;
    public $router_list;
    public $path_dir_dao;
    public $extend_dao;
    public $template_dao;
    public $log_dir;


    public function run()
    {

        \Pf\Core\Runtime::register();
        \Pf\Core\Exception::register();
        \Pf\Core\Shutdown::register();
        \Pf\Core\Error::register();
        \Pf\Core\Env::register();
        \Pf\Core\Helper::register();
        \Pf\Core\Conf::register();
        \Pf\Core\Route::register();
        \Pf\Core\Origin::check();
        \Pf\Core\Uri::check();
        \Pf\Core\Method::check();
        \Pf\Core\Dao::build();
        \Pf\Core\Middleware::before();
        \Pf\Core\Business::process();
        \Pf\Core\Middleware::after();
        \Pf\Core\Render::success();

    }


}
