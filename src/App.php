<?php


namespace Pf;


use Pf\Core\Error;
use Pf\Core\Exception;
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

    }


    public function run()
    {

    }


}
