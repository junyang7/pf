<?php


namespace Pf\Core;


class Conf
{


    const EXTEND = '.php';
    const LENGTH = 4;
    const COMMON = _PF_DIR . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR;
    public static $env = _PF_DIR . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR . 'env' . DIRECTORY_SEPARATOR;


    public static function register()
    {

        $file_part_list = [];

        if(file_exists(self::COMMON) && is_dir(self::COMMON))
        {
            $file_part_list[] = Dir::getFileList(self::COMMON, 0, self::EXTEND, self::LENGTH, TRUE);
        }

        if(defined('APP_ENV'))
        {

            self::$env .= APP_ENV . DIRECTORY_SEPARATOR;

            if(file_exists(self::$env) && is_dir(self::$env))
            {
                $file_part_list[] = Dir::getFileList(self::$env, 0, self::EXTEND, self::LENGTH, TRUE);
            }

        }

        foreach($file_part_list as $file_part)
        {

            ksort($file_part);

            foreach($file_part as $file_list)
            {

                foreach($file_list as $file => $path)
                {
                    $conf_list[substr($file, 0, -self::LENGTH)] = require_once $path;
                }

            }

        }

        define('_PF_APP_CONF_LIST', $conf_list);

    }


}
