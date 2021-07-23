<?php


namespace Pf\Core;


class Business
{


    public static function process()
    {

        $app = \Pf\App::getInstance();
        $app->response->body = call_user_func(
            [
                new $app->request->router['controller'](),
                $app->request->router['action'],
            ],
            $app->request
        );

    }


}
