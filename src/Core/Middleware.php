<?php


namespace Pf\Core;


class Middleware
{


    public static function before()
    {

        $app = \Pf\App::getInstance();

        foreach($app->request->router['middleware_list'] as $middleware)
        {

            $class = c('middleware.' . $middleware);

            if(!class_exists($class))
            {
                throw new PfException(-1, '中间件类不存在', ['class' => $class, ]);
            }

            if(method_exists($class, 'before'))
            {
                $app->request = $class::before($app->request);
            }

        }

    }


    public static function after()
    {

        $app = \Pf\App::getInstance();

        foreach($app->request->router['middleware_list'] as $middleware)
        {

            $class = c('middleware.' . $middleware);

            if(!class_exists($class))
            {
                throw new PfException(-1, '中间件类不存在', ['class' => $class, ]);
            }

            if(method_exists($class, 'after'))
            {
                $app->response = $class::after($app->response);
            }

        }

    }


}
