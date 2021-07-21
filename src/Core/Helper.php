<?php


namespace Pf\Core;


class Helper
{


    private static $callback_list = ['c', 'i', ];


    public static function register()
    {

        if(empty(self::$callback_list))
        {
            return;
        }

        foreach(self::$callback_list as $callback)
        {
            self::$callback();
        }

    }


    public static function c()
    {

        if(!function_exists('C'))
        {

            function C($name, $default = NULL)
            {

                $key_list = explode('.', $name);
                $conf = Request::getInstance()->conf_list;

                foreach($key_list as $key)
                {

                    if(!isset($conf[$key]))
                    {
                        return $default;
                    }

                    $conf = $conf[$key];

                }

                return $conf;

            }

        }

    }


    public static function i()
    {

        if(!function_exists('I'))
        {
            function I($ok)
            {

                if(!$ok)
                {
                    throw new PfException(-1, 'info', 'data');
                }

            }
        }

    }


}
