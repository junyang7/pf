<?php

namespace Junyang7\PhpCommon;

class _Context
{

    public $storage = [];

    public function __construct()
    {

        if (isset($_SERVER["HTTP_CONTENT_TYPE"]) && strtolower($_SERVER["HTTP_CONTENT_TYPE"]) === "application/json") {
            $_POST = _Json::decode($this->input());
            foreach ($_POST as $k => $v) {
                $_REQUEST[$k] = $v;
            }
        }

    }

    public function get($name, $default = "")
    {

        return $_GET[$name] ?? $default;

    }

    public function getInt($name, $default = 0)
    {

        return (int)($_GET[$name] ?? $default);

    }

    public function getAll()
    {

        return $_GET;

    }

    public function post($name, $default = "")
    {

        return $_POST[$name] ?? $default;

    }

    public function postInt($name, $default = 0)
    {

        return (int)($_POST[$name] ?? $default);

    }

    public function postAll()
    {

        return $_POST;

    }

    public function request($name, $default = "")
    {

        return $_REQUEST[$name] ?? $default;

    }

    public function requestInt($name, $default = 0)
    {

        return (int)($_REQUEST[$name] ?? $default);

    }

    public function requestAll()
    {

        return $_REQUEST;

    }

    public function server($name, $default = "")
    {

        return $_SERVER[$name] ?? $default;

    }

    public function serverInt($name, $default = 0)
    {

        return (int)($_SERVER[$name] ?? $default);

    }

    public function serverAll()
    {

        return $_SERVER;

    }

    public function cookie($name, $default = "")
    {

        return $_COOKIE[$name] ?? $default;

    }

    public function cookieInt($name, $default = 0)
    {

        return (int)($_COOKIE[$name] ?? $default);

    }

    public function cookieAll()
    {

        return $_COOKIE;

    }

    public function file($name)
    {

        return $_FILES[$name] ?? null;

    }

    public function fileAll()
    {

        return $_FILES;

    }

    public function input()
    {

        $input = file_get_contents("php://input");
        if (in_array($this->server("HTTP_CONTENT_ENCODING"), ["gzip", "x-gzip",])) {
            $input = gzdecode($input);
        }
        return $input;

    }

    public function JSON($data = null)
    {

        $response = new _Response();
        $response->data = $data;
        $response->JSON();

    }

    public function HTML($data = null)
    {

        $response = new _Response();
        $response->data = $data;
        $response->HTML();

    }

}
