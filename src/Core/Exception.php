<?php


namespace Pf\Core;


class Exception
{


    public static function register()
    {

        set_exception_handler([self::class, 'handleException', ]);

    }


    public static function handleException($exception)
    {

        if(get_class($exception) == 'Pf\Core\PfException')
        {
            echo 'code: ' . $exception->getPfCode() . PHP_EOL;
            echo 'info: ' . $exception->getPfInfo() . PHP_EOL;
            echo 'data: ' . json_encode($exception->getPfData(), JSON_UNESCAPED_UNICODE) . PHP_EOL;
        }
        else
        {
            echo 'code: ' . $exception->getCode() . PHP_EOL;
            echo 'info: ' . $exception->getMessage() . PHP_EOL;
            echo 'data: ' . json_encode($exception->getTrace(), JSON_UNESCAPED_UNICODE) . PHP_EOL;
        }
        echo 'file: ' . $exception->getFile() . PHP_EOL;
        echo 'line: ' . $exception->getLine() . PHP_EOL;

    }


}
