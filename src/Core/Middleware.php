<?php


namespace Pf\Core;


class Middleware
{


    public static function before()
    {

        $request = Request::getInstance();

        foreach($request->router['middleware_list'] as $middleware)
        {

            $class = c('middleware.' . $middleware);

            if(!class_exists($class))
            {
                throw new PfException(-1, '中间件类不存在', ['class' => $class, ]);
            }

            if(method_exists($class, 'before'))
            {
                $class::before($request);
            }

        }

    }


    public static function after()
    {

        $request = Request::getInstance();

        foreach($request->router['middleware_list'] as $middleware)
        {

            $class = c('middleware.' . $middleware);

            if(!class_exists($class))
            {
                throw new PfException(-1, '中间件类不存在', ['class' => $class, ]);
            }

            if(method_exists($class, 'after'))
            {
                $class::after($request);
            }

        }

    }


}
