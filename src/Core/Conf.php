<?php


namespace Pf\Core;


class Conf
{


    public static function register()
    {

        $request = Request::getInstance();
        $file_part_list = [];

        if(file_exists($request->path_dir_common) && is_dir($request->path_dir_common))
        {
            $file_part_list[] = Dir::getFileList($request->path_dir_common, 0, $request->extend_conf, $request->length_extend_conf, TRUE);
        }

        if(defined('APP_ENV'))
        {

            $request->path_dir_env .= APP_ENV . DIRECTORY_SEPARATOR;

            if(file_exists($request->path_dir_env) && is_dir($request->path_dir_env))
            {
                $file_part_list[] = Dir::getFileList($request->path_dir_env, 0, $request->extend_conf, $request->length_extend_conf, TRUE);
            }

        }

        foreach($file_part_list as $file_part)
        {

            ksort($file_part);

            foreach($file_part as $file_list)
            {

                foreach($file_list as $file => $path)
                {
                    $request->conf_list[substr($file, 0, -$request->length_extend_conf)] = require_once $path;
                }

            }

        }

    }


}
