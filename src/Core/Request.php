<?php


namespace Pf\Core;


class Request
{


    private static $instance;
    private function __construct()
    {

    }
    private function __clone()
    {

    }
    public static function getInstance()
    {

        if(!isset(self::$instance) || !self::$instance instanceof self)
        {
            self::$instance = new self();
        }

        return self::$instance;


    }


    public $env = '';
    public $router;
    public $uri = '';
    public $method = '';
    public $context = [];


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
            throw new \Pf\Core\PfException(-1, '参数错误', ['name' => $name, ]);
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


}
