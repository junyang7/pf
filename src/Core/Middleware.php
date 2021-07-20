<?php


namespace Pf\Core;


class Middleware
{


    public static function before($request)
    {

        foreach($request->route['middleware_list'] as $middleware)
        {

            $class = c('middleware.' . $middleware);

            if(!class_exists($class))
            {
                throw new PfException(-1, '中间件类不存在', ['class' => $class, ]);
            }

            if(method_exists($class, 'before'))
            {
                $request = $class::before($request);
            }

        }

        return $request;

    }


    public static function after($request, $response)
    {

        foreach($request->route['middleware_list'] as $middleware)
        {

            $class = c('middleware.' . $middleware);

            if(!class_exists($class))
            {
                throw new PfException(-1, '中间件类不存在', ['class' => $class, ]);
            }

            if(method_exists($class, 'after'))
            {
                $response = $class::after($response);
            }

        }

        return $response;

    }


}
