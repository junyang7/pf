<?php


namespace Pf\Core;


class Redis
{


    private static $instance_list;
    private function __construct()
    {

    }
    private function __clone()
    {

    }


    public static function getInstance($name = 0)
    {

        if(!isset(self::$instance_list[$name]) || !self::$instance_list[$name] instanceof \Redis)
        {

            $connection = C('redis.' . $name);
            self::$instance_list[$name] = new \Redis();
            self::$instance_list[$name]->connect($connection['host'], $connection['port']);

            if(isset($connection['password']) && !empty($connection['password']))
            {
                self::$instance_list[$name]->auth($connection['password']);
            }

        }

        return self::$instance_list[$name];

    }


}
