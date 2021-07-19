<?php


namespace Pf;


use Pf\Core\Runtime;


class App
{


    public function __construct($base_dir)
    {

        Runtime::register($base_dir);

    }


    public function run()
    {

        var_dump(_PF_DIR);
        var_dump(_PF_ENV);
        var_dump(ini_get('error_reporting'));
        var_dump(ini_get('display_errors'));

    }


}
