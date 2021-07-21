<?php


namespace Pf\Core;


class Table
{


    public static function register()
    {

        $request = Request::getInstance();
        $table_list = C('table');

        if(empty($table_list))
        {
            return;
        }

        Dir::createIfNotExists($request->path_dir_model);

        foreach($table_list as $name => $conf)
        {

            $class_name = str_replace('_', '', ucwords(strtolower($name), '_'));
            file_put_contents($request->path_dir_model . $class_name . $request->extend_model, sprintf($request->template_model, $class_name, $name));

        }

    }


}
