<?php


namespace Pf\Core;


class Conf
{


    public static function register()
    {

        $app = \Pf\App::getInstance();
        $file_part_list = [];

        if(file_exists($app->path_dir_common) && is_dir($app->path_dir_common))
        {
            $file_part_list[] = Dir::getFileList($app->path_dir_common, 0, $app->extend_conf, $app->length_extend_conf, TRUE);
        }

        if(defined('APP_ENV'))
        {

            $app->path_dir_env .= APP_ENV . DIRECTORY_SEPARATOR;

            if(file_exists($app->path_dir_env) && is_dir($app->path_dir_env))
            {
                $file_part_list[] = Dir::getFileList($app->path_dir_env, 0, $app->extend_conf, $app->length_extend_conf, TRUE);
            }

        }

        foreach($file_part_list as $file_part)
        {

            ksort($file_part);

            foreach($file_part as $file_list)
            {

                foreach($file_list as $file => $path)
                {
                    $app->conf_list[substr($file, 0, -$app->length_extend_conf)] = require_once $path;
                }

            }

        }

    }


}
