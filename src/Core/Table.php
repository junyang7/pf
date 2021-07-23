<?php


namespace Pf\Core;


class Table
{


    public static function register()
    {

        $app = \Pf\App::getInstance();
        $table_list = C('table');

        if(empty($table_list))
        {
            return;
        }

        Dir::createIfNotExists($app->path_dir_dao);

        foreach($table_list as $name => $conf)
        {

            $class_name = str_replace('_', '', ucwords(strtolower($name), '_'));
            file_put_contents($app->path_dir_dao . $class_name . $app->extend_dao, sprintf($app->template_dao, $class_name, $name));

        }

    }


}
