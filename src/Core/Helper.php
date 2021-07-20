<?php


namespace Pf\Core;


class Helper
{


    public static function register()
    {

        self::c();

    }


    public static function c()
    {

        if(function_exists('C'))
        {
            return;
        }

        function C($name, $default = NULL)
        {

            $key_list = explode('.', $name);

            if(!defined('_PF_APP_CONF_LIST'))
            {
                return $default;
            }

            $conf = _PF_APP_CONF_LIST;

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
