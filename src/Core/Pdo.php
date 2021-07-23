<?php


namespace Pf\Core;


class Pdo
{


    private function __construct()
    {

    }
    private function __clone()
    {

    }


    public static $pdo_list = [];
    public static function getInstance($connection)
    {

        $uk = md5(serialize($connection));

        if(!isset(self::$pdo_list[$uk]) || !self::$pdo_list[$uk] instanceof \PDO)
        {

            self::$pdo_list[$uk] = new \PDO(
                sprintf('%s:host=%s;port=%s;dbname=%s', $connection['driver'], $connection['host'], $connection['port'], $connection['database']),
                $connection['username'],
                $connection['password'],
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::MYSQL_ATTR_INIT_COMMAND => sprintf('SET NAMES %s', $connection['charset']),
                    \PDO::ATTR_EMULATE_PREPARES => TRUE,
                    \PDO::ATTR_PERSISTENT => TRUE,
                ]
            );
        }

        return self::$pdo_list[$uk];

    }


}
