<?php


namespace Pf\Core;


class Request
{


    public $uri = '';
    public $method = '';
    public $route = [];
    public $context = [];


    public function __construct()
    {

        $this->_checkOrigin();
        $this->_checkUri();
        $this->_checkMethod();

    }


    public function get($name, $default = '')
    {

        return $this->_row($_GET, $name, $default);

    }


    public function getList($parameter_list = [])
    {

        return $this->_rowList($_GET, $parameter_list);

    }


    public function post($name, $default = '')
    {

        return $this->_row($_POST, $name, $default);

    }


    public function postList($parameter_list = [])
    {

        return $this->_rowList($_POST, $parameter_list);

    }


    public function request($name, $default = '')
    {

        return $this->_row($_REQUEST, $name, $default);

    }


    public function requestList($parameter_list = [])
    {

        return $this->_rowList($_REQUEST, $parameter_list);

    }


    public function file($name, $default = '')
    {

        return $this->_row($_FILES, $name, $default);

    }


    public function fileList($parameter_list = [])
    {

        return $this->_rowList($_FILES, $parameter_list);

    }


    public function server($name, $default = '')
    {

        return $this->_row($_SERVER, $name, $default);

    }


    public function serverList($parameter_list = [])
    {

        return $this->_rowList($_SERVER, $parameter_list);

    }


    public function cli($name, $default = '')
    {

        return $this->_row($_SERVER, $name, $default);

    }


    public function cliList($parameter_list = [])
    {

        return $this->_rowList($_SERVER, $parameter_list);

    }


    public function cookie($name, $default = '')
    {

        return $this->_row($_COOKIE, $name, $default);

    }


    public function cookieList($parameter_list = [])
    {

        return $this->_rowList($_COOKIE, $parameter_list);

    }


    private function _row($target, $name, $default)
    {

        if(!is_string($name) || empty($name))
        {

            throw new PfException(-1, '参数错误', ['name' => $name, ]);

        }

        return isset($target[$name]) ? $target[$name] : $default;

    }


    private function _rowList($target, $parameter_list)
    {

        foreach($parameter_list as $name => $default)
        {
            $parameter_list[$name] = $this->_row($target, $name, $default);
        }

        return $parameter_list;

    }


    private function _checkOrigin()
    {

        if(!empty($http_origin = trim($this->server('HTTP_ORIGIN'), '/')))
        {

            if(is_string($http_origin) && !empty($http_origin))
            {

                $origin = parse_url($http_origin);

                if(defined('ACCESS_CONTROL_ALLOW_ORIGIN'))
                {

                    if(ACCESS_CONTROL_ALLOW_ORIGIN == '*' || is_array(ACCESS_CONTROL_ALLOW_ORIGIN) && in_array($origin['host'], ACCESS_CONTROL_ALLOW_ORIGIN))
                    {
                        header('Access-Control-Allow-Origin: ' . $origin['scheme'] . '://' . $origin['host'] . (isset($origin['port']) ? ':' . $origin['port'] : ''));
                        return;
                    }

                }

            }

            header('HTTP/1.1 403 Forbidden');
            exit();

        }

    }


    private function _checkUri()
    {

        if(_PF_ENV != _PF_ENV_CLI)
        {

            if(empty($this->uri = $this->server('REQUEST_URI')) || empty($this->method = $this->server('REQUEST_METHOD')))
            {
                throw new PfException(-1, '参数错误', NULL);
            }

        }
        else
        {

            if($this->cli('argc') <= 2)
            {
                throw new PfException(-1, '参数错误', NULL);
            }

            $this->uri = $this->cli('argv')[1];
            $this->method = strtoupper(_PF_ENV_CLI);

        }

        if(isset(_PF_APP_ROUTE_LIST[$this->uri]))
        {

            $this->route = _PF_APP_ROUTE_LIST[$this->uri];

        }
        else
        {

            foreach(_PF_APP_ROUTE_LIST as $uri => $route)
            {

                if(strlen($uri) < 2 || $uri[0] != '/' || $uri[strlen($uri) - 1] != '/')
                {
                    continue;
                }

                foreach($route['extend_list'] as $parameter => $pattern)
                {

                    $needle = '{' . $parameter . '}';

                    if(stripos($uri, $needle))
                    {
                        $uri = str_replace($needle, $pattern, $uri);
                    }

                }

                if(preg_match($uri, $this->uri, $match) == 1)
                {

                    $i = 1;

                    foreach($route['extend_list'] as $parameter => $pattern)
                    {

                        $this->context[$parameter] = $match[$i ++];

                    }

                    $this->route = $route;
                    break;

                }

            }

        }

        if(empty($this->route))
        {
            throw new PfException(-1, '路由未定义', ['uri' => $this->uri, ]);
        }

    }


    private function _checkMethod()
    {

        if($this->method == 'OPTIONS')
        {
            exit();
        }

        if(!in_array($this->method, $this->route['method_list']))
        {
            throw new PfException(-1, '请求方法不允许', ['method' => $this->method, ]);
        }

    }


}
