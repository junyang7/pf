<?php


namespace Pf;


use Pf\Core\Conf;
use Pf\Core\Env;
use Pf\Core\Error;
use Pf\Core\Exception;
use Pf\Core\Helper;
use Pf\Core\Route;
use Pf\Core\Runtime;
use Pf\Core\Shutdown;


class App
{


    public function __construct($base_dir)
    {

        Runtime::register($base_dir);
        Exception::register();
        Shutdown::register();
        Error::register();
        Env::register();
        Conf::register();
        Helper::register();
        Route::register();

    }


    public function run()
    {

    }


}
