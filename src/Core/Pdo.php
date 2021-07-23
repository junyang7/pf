<?php


namespace Pf\Core;


class Pdo
{


    private static $pdo_list = [];
    private function __construct()
    {

    }
    private function __clone()
    {

    }
    private static function ok(\PDO $pdo)
    {

        try
        {
            $pdo->getAttribute(\PDO::ATTR_SERVER_INFO);
        }
        catch(\Exception $exception)
        {

            if(in_array($pdo->errorInfo()[1], [2006, 2013, ]))
            {
                return false;
            }

        }

        return true;

    }


    public static function getInstance($connection)
    {

        $uk = md5(serialize($connection));

        if(!isset(self::$pdo_list[$uk]) || !self::$pdo_list[$uk] instanceof \PDO || !self::ok(self::$pdo_list[$uk]))
        {

            self::$pdo_list[$uk] = NULL;
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
            self::$pdo_list[$uk]->exec('SET NAMES ' . $connection['charset']);
            self::$pdo_list[$uk]->exec('SET character_set_client=binary');

        }

        return self::$pdo_list[$uk];

    }


}
