<?php


namespace Pf\Core;


class Request
{


    private $_SERVER;


    public function __construct()
    {

        $this->_SERVER = $_SERVER;

    }


    private function _checkOrigin()
    {

        if(isset($this->_SERVER['HTTP_ORIGIN']))
        {

            $http_origin = trim($this->_SERVER['HTTP_ORIGIN'], '/');

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


}
