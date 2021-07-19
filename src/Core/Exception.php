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
            var_dump($exception->getPfCode());
            var_dump($exception->getPfInfo());
            var_dump($exception->getPfData());
            var_dump($exception->getFile());
            var_dump($exception->getLine());
        }
        else
        {
            var_dump($exception);
        }

    }


}
