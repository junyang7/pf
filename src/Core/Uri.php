<?php


namespace Pf\Core;


class Uri
{


    public static function check()
    {

        $app = \Pf\App::getInstance();

        if($app->request->env == $app->env_cli)
        {

            if($app->request->cli('argc') < 2)
            {
                throw new \Pf\Core\PfException(-1, '参数错误', ['argc' => $app->request->cli('argc'), ]);
            }

            $app->request->method = strtoupper($app->env_cli);
            $app->request->uri = $app->request->cli('argv')[1];
            Log::access($app->request->method . "\t" . $app->request->uri, $_SERVER);

        }
        else
        {

            if(empty($app->request->uri = $app->request->server('REQUEST_URI')))
            {
                throw new \Pf\Core\PfException(-1, '参数错误', ['uri' => $app->request->uri, ]);
            }

            if(empty($app->request->method = $app->request->server('REQUEST_METHOD')))
            {
                throw new \Pf\Core\PfException(-1, '参数错误', ['method' => $app->request->method, ]);
            }

            Log::access($app->request->method . "\t" . $app->request->uri, $_REQUEST);

        }

        if(isset($app->router_list[$app->request->uri]))
        {
            $app->request->router = $app->router_list[$app->request->uri];
        }
        else
        {

            foreach($app->router_list as $uri => $router)
            {

                if(($uri_length = strlen($uri)) < 2 || $uri[0] != '/' || $uri[$uri_length - 1] != '/')
                {
                    continue;
                }

                foreach($router['extend_list'] as $parameter => $pattern)
                {

                    $needle = '{' . $parameter . '}';

                    if(stripos($uri, $needle))
                    {
                        $uri = str_replace($needle, $pattern, $uri);
                    }

                }

                if(preg_match($uri, $app->request->uri, $match) == 1)
                {

                    $i = 1;

                    foreach($router['extend_list'] as $parameter => $pattern)
                    {
                        $app->request->context[$parameter] = $match[$i++];
                    }

                    $app->request->router = $router;
                    break;

                }

            }

        }

        if(empty($app->request->router))
        {
            throw new \Pf\Core\PfException(-1, '路由未定义', ['uri' => $app->request->uri, ]);
        }

    }


}
