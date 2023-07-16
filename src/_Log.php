<?php

namespace Junyang7\PhpCommon;

class _Log
{

    public static function shutdown(...$message)
    {

        self::write("shutdown", $message);

    }

    public static function error(...$message)
    {

        self::write("error", $message);

    }

    public static function exception(...$message)
    {

        self::write("exception", $message);

    }

    public static function request(...$message)
    {

        self::write("request", $message);

    }

    public static function success(...$message)
    {

        self::write("success", $message);

    }

    public static function debug(...$message)
    {

        self::write("debug", $message);

    }

    public static function warn(...$message)
    {

        self::write("warn", $message);

    }

    public static function custom($name, ...$message)
    {

        self::write($name, $message);

    }

    public static function write($name, $message)
    {

        if (!C("log.enable", false)) {
            return;
        }
        if (empty($path = C("log.path", ""))) {
            return;
        }
        $content = _Datetime::get() . "\t" . _Json::encode($message) . "\n";
        $filename = $path . "/" . $name . "." . _Date::getByFormat("Ymd");
        if (!file_exists($path)) {
            try {
                _Cmd::do(sprintf("mkdir -p %s", $path));
            } catch (\Exception $exception) {
                try {
                    file_put_contents("default.log", $exception->getMessage() . "\n", FILE_APPEND);
                    file_put_contents("default.log", $content, FILE_APPEND);
                } catch (\Exception $exception) {
                    return;
                }
            }
        }
        if (!is_dir($path)) {
            return;
        }
        try {
            file_put_contents($filename, $content, FILE_APPEND);
        } catch (\Exception $exception) {
            return;
        }

    }

}
