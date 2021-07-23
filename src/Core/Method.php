<?php


namespace Pf\Core;


class Method
{


    public static function check()
    {

        $app = \Pf\App::getInstance();

        if($app->request->method == 'OPTIONS')
        {
            exit();
        }

        if(!in_array($app->request->method, $app->request->router['method_list']))
        {
            throw new \Pf\Core\PfException(-1, '请求方法不允许', ['method' => $app->request->method, ]);
        }

    }


}
