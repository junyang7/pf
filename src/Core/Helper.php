<?php


namespace Pf\Core;


class Helper
{


    public static function register()
    {

        if(!function_exists('C'))
        {

            function C($name, $default = NULL)
            {

                $name_list = explode('.', $name);
                $conf = \Pf\App::getInstance()->conf_list;

                foreach($name_list as $name)
                {

                    if(!isset($conf[$name]))
                    {
                        return $default;
                    }

                    $conf = $conf[$name];

                }

                return $conf;

            }

        }

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
