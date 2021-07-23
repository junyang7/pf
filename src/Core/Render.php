<?php


namespace Pf\Core;


class Render
{


    public static function success()
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
                        'data' => $app->response->body,
                    ]
                );
                break;
            default:
                echo $app->response->body;
                break;
        }

    }


    public static function exception($exception)
    {

        $app = \Pf\App::getInstance();
        $app->response = [];

        if(get_class($exception) == 'Pf\Core\PfException')
        {

            $app->response['code'] = $exception->getPfCode();
            $app->response['info'] = $exception->getPfInfo();
            $app->response['data'] = $exception->getPfData();

        }
        else
        {

            $app->response['code'] = -1;
            $app->response['info'] = $exception->getMessage();
            $app->response['data'] = $exception->getTrace();

        }

        if(defined('APP_DEBUG') && constant('APP_DEBUG'))
        {

            $app->response['file'] = $exception->getFile();
            $app->response['line'] = $exception->getLine();

        }
        else
        {

            if(get_class($exception) != 'Pf\Core\PfException')
            {

                $app->response['info'] = '系统繁忙，稍后再试';
                $app->response['data'] = NULL;

            }

        }

        switch($app->request->env)
        {
            case $app->env_api:
                header('Content-Type: application/json');
                echo json_encode($app->response);
                break;
            default:
                echo $app->response['info'];
                break;
        }

    }


}
