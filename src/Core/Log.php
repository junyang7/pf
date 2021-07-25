<?php


namespace Pf\Core;


class Log
{


    private function __construct()
    {

    }
    private function __clone()
    {

    }


    public static function access($info = '', $data = [])
    {

        self::write(__FUNCTION__, $info, $data);

    }
    public static function warning($info = '', $data = [])
    {

        self::write(__FUNCTION__, $info, $data);

    }
    public static function error($info = '', $data = [])
    {

        self::write(__FUNCTION__, $info, $data);

    }
    public static function exception($info = '', $data = [])
    {

        self::write(__FUNCTION__, $info, $data);

    }
    public static function shutdown($info = '', $data = [])
    {

        self::write(__FUNCTION__, $info, $data);

    }
    public static function interceptor($info = '', $data = [])
    {

        self::write(__FUNCTION__, $info, $data);

    }
    public static function custom($name, $info = '', $data = [])
    {

        self::write($name . '.' . __FUNCTION__, $info, $data);

    }


    public static function write($prefix, $info = '', $data = '')
    {

        $app = \Pf\App::getInstance();
        $app->log_dir = defined('LOG_DIR')
            ? LOG_DIR
            : $app->path_dir_base . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR
        ;
        Dir::createIfNotExists($app->log_dir);
        $time = explode('.', microtime(true));
        file_put_contents(
            $app->log_dir . $prefix . '.' . date('Ymd', $time[0]),
            date('Y-m-d H:i:s', $time[0]) . '.' . str_pad($time[1], 5, 0, STR_PAD_RIGHT)
            . "\t"
            . (is_scalar($info) ? $info : json_encode($info, JSON_UNESCAPED_UNICODE))
            . "\t"
            . (is_scalar($data) ? $data : json_encode($data, JSON_UNESCAPED_UNICODE))
            . PHP_EOL
            ,
            FILE_APPEND
        );

    }


}
