<?php


namespace Pf\Core;


class Env
{


    const ENV = _PF_DIR . '/conf/env.php';


    public static function register()
    {

        if(file_exists(self::ENV))
        {
            require_once self::ENV;
        }

    }


}
