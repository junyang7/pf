<?php


namespace Pf\Core;


class Render
{


    public static function success($response)
    {

        $app = \Pf\App::getInstance();

        switch($app->request->env)
        {
            case $app->env_api:
                header('Content-Type: application/json');
                echo json_encode(
                    [
                        'code' => 0,
                        'info' => 'success',
                        'data' => $response->body,
                    ]
                );
                break;
            default:
                echo $response->body;
                break;
        }

    }


}
