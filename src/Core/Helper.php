<?php


namespace Pf\Core;


class Helper
{


    const FILE = 'Function.php';


    public static function register()
    {

        if(file_exists(self::FILE))
        {
            require_once self::FILE;
        }


    }


}
