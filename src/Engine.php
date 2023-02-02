<?php

namespace Junyang7\Pf;

use Junyang7\PhpCommon\_Array;
use Junyang7\PhpCommon\_Directory;
use Junyang7\PhpCommon\_File;
use Junyang7\PhpCommon\_Json;
use Junyang7\PhpCommon\_String;
use Junyang7\PhpCommon\_Conf;
use Junyang7\PhpCommon\_Context;
use Junyang7\PhpCommon\_Exception;
use Junyang7\PhpCommon\_Ip;
use Junyang7\PhpCommon\_Log;

class Engine
{

    private $context;

    public function __construct()
    {

        $this->context = new _Context();

    }

    public function run()
    {

        $this->registerException();
        $this->registerHelper();
        $this->registerConf();

        if (php_sapi_name() == "cli") {
            return;
        }

        _Log::request($this->context);

        $this->checkIp();
        $this->checkMethod();
        $this->checkOrigin();
        $this->registerRouter();
        $this->checkRouter();
        $this->checkRouterMethod();
        $this->middlewareBefore();
        $this->business();
        $this->middlewareAfter();
        $this->render();

    }

    private function registerException()
    {

        register_shutdown_function(
            function () {
                if ($error = error_get_last()) {
                    $this->context->code = _Exception::SHUTDOWN[0];
                    $this->context->message = _Exception::SHUTDOWN[1];
                    $this->context->data_system = [$error["type"], $error["message"],];
                    $this->context->file = $error["file"];
                    $this->context->line = $error["line"];
                    _Log::shutdown($this->context);
                    $this->render();
                    exit();
                }
            }
        );

        set_error_handler(
            function ($code, $message, $file, $line) {
                $this->context->code = _Exception::ERROR[0];
                $this->context->message = _Exception::ERROR[1];
                $this->context->data_system = [$code, $message,];
                $this->context->file = $file;
                $this->context->line = $line;
                _Log::error($this->context);
                $this->render();
                exit();
            }
        );

        set_exception_handler(
            function ($exception) {
                $this->context->file = $exception->getFile();
                $this->context->line = $exception->getLine();
                $this->context->trace = $exception->getTraceAsString();
                if ($exception instanceof _Exception) {
                    $this->context->code = $exception->exception[0];
                    $this->context->message = $exception->exception[1];
                    $this->context->data = $exception->data_user;
                    $this->context->data_system = $exception->data_system;
                } else {
                    $this->context->code = _Exception::EXCEPTION[0];
                    $this->context->message = _Exception::EXCEPTION[1];
                    $this->context->data_system = [$exception->getCode(), $exception->getMessage(),];
                }
                _Log::exception($this->context);
                $this->render();
                exit();
            }
        );

    }

    private function registerHelper()
    {

        require_once "Helper.php";

    }

    private function registerConf()
    {

        $load = function ($path) {
            $file_list = _Directory::read(_Conf::$wd . "/conf" . $path);
            $res = [];
            foreach ($file_list as $file) {
                $info = pathinfo($file);
                switch ($info["extension"]) {
                    case "php":
                        $res[$info["filename"]] = require_once $file;
                        break;
                    default:
                        $res[$info["filename"]] = _File::read($file);
                        break;
                }
            }
            return $res;
        };

        _Conf::$conf = $load("/common");

        $env = "dev";
        try {
            $_env = trim(_File::read(_Conf::$wd . "/env"));
            if (!empty($_env)) {
                $env = $_env;
            }
        } catch (_Exception $exception) {

        }

        _Array::merge(_Conf::$conf, $load("/env" . "/" . $env));

        if (isset(_Conf::$conf["ini"])) {
            foreach (_Conf::$conf["ini"] as $k => $v) {
                ini_set($k, $v);
            }
        }

    }

    private function checkIp()
    {

        // 黑名单
        if (isset(_Conf::$conf["ipv4"]["black"])) {
            if (_Ip::in(_Conf::$client_ip, _Conf::$conf["ipv4"]["black"])) {
                throw new _Exception(_Exception::IP_IN_BLACK, ["ipv4" => _Conf::$client_ip,]);
            }
        }

        // 白名单
        if (isset(_Conf::$conf["ipv4"]["white"])) {
            if (!_Ip::in(_Conf::$client_ip, _Conf::$conf["ipv4"]["white"])) {
                throw new _Exception(_Exception::IP_NOT_IN_WHITE, ["ipv4" => _Conf::$client_ip,]);
            }
        }

    }

    private function checkMethod()
    {

        // 黑名单
        if (isset(_Conf::$conf["method"]["black"])) {
            if (in_array($this->context->method, _Conf::$conf["method"]["black"])) {
                throw new _Exception(_Exception::METHOD_IN_BLACK, ["method" => $this->context->method,]);
            }
        }

        // 白名单
        if (isset(_Conf::$conf["method"]["white"])) {
            if (!in_array($this->context->method, _Conf::$conf["method"]["white"])) {
                throw new _Exception(_Exception::METHOD_NOT_IN_WHITE, ["method" => $this->context->method,]);
            }
        }

    }

    private function checkOrigin()
    {

        $origin = $this->context->server("HTTP_ORIGIN")->string();
        if (empty($origin) || preg_match("/([^:]+):\/\/([^:]+):?(\d+)?/", $origin, $matched) != 1) {
            return;
        }

        if (isset(_Conf::$conf["origin"]) && !empty($origin_list = _Conf::$conf["origin"])) {
            foreach ($origin_list as $_origin) {
                if ($_origin == "*" || $matched[2] == $_origin || $_origin[0] == "." && _String::hasSuffix($matched[2], $_origin)) {
                    _Conf::$conf["header"]["access-control-allow-origin"] = $origin;
                    return;
                }
            }
        }

        throw new _Exception(_Exception::ORIGIN_FORBID, ["origin" => $origin,]);

    }

    private function registerRouter()
    {

        try {
            $file_list = _Directory::read(_Conf::$wd . "/router");
        } catch (_Exception $exception) {
            return;
        }

        foreach ($file_list as $file) {
            $info = pathinfo($file);
            switch ($info["extension"]) {
                case "php":
                    require_once $file;
                    break;
                default:
                    break;
            }
        }

    }

    private function checkRouter()
    {

        if (isset(_Conf::$router_list[$this->context->path])) {
            $this->context->router = _Conf::$router_list[$this->context->path];
            return;
        }

        foreach (_Conf::$router_list as $router) {
            if (empty($router->path_pattern)) {
                continue;
            }
            if (preg_match($router->path_pattern, $this->context->path, $matched)) {
                array_shift($matched);
                foreach ($router->path_parameter as $index => $parameter) {
                    if (!empty($parameter)) {
                        $_GET[$parameter] = $matched[$index] ?? "";
                    }
                }
                $this->context->router = $router;
                return;
            }
        }

        throw new _Exception(_Exception::ROUTER_NOT_EXISTS, ["path" => $this->context->path,]);

    }

    private function checkRouterMethod()
    {

        if (in_array($this->context->method, $this->context->router->method_list)) {
            return;
        }

        throw new _Exception(_Exception::ROUTER_METHOD_FORBID, ["method" => $this->context->method,]);

    }

    private function middlewareBefore()
    {

        foreach ($this->context->router->middleware_list as $middleware) {

            if (!class_exists($middleware)) {
                throw new _Exception(_Exception::MIDDLEWARE_NOT_EXISTS, [], ["middleware" => $middleware,]);
            }

            if (method_exists($middleware, "before")) {
                call_user_func([$middleware, "before",], $this->context);
            }

        }

    }

    private function business()
    {

        if (!class_exists($this->context->router->controller)) {
            throw new _Exception(_Exception::CONTROLLER_NOT_EXISTS, [], ["controller" => $this->context->router->controller,]);
        }

        if (!method_exists($this->context->router->controller, $this->context->router->action)) {
            throw new _Exception(_Exception::CONTROLLER_ACTION_NOT_EXISTS, [], ["controller" => $this->context->router->controller, "action" => $this->context->router->action,]);
        }

        $this->context->data = call_user_func([new $this->context->router->controller(), $this->context->router->action], $this->context);

    }

    private function middlewareAfter()
    {

        foreach ($this->context->router->middleware_list as $middleware) {

            if (!class_exists($middleware)) {
                throw new _Exception(_Exception::MIDDLEWARE_NOT_EXISTS, [], ["middleware" => $middleware,]);
            }

            if (method_exists($middleware, "after")) {
                call_user_func([$middleware, "after",], $this->context);
            }

        }

    }

    private function render()
    {

        if (empty($this->context->data)) {
            $this->context->data = new \stdClass();
        }

        _Conf::$time_e = microtime(true);
        $this->context->time = intval(_Conf::$time_e);

        if (_Conf::$time_s) {
            $this->context->consume = intval((_Conf::$time_e - _Conf::$time_s) * 1000);
        }

        if (isset(_Conf::$conf["header"]) && is_array(_Conf::$conf["header"]) && !headers_sent()) {
            foreach (_Conf::$conf["header"] as $k => $v) {
                header(sprintf("%s: %s", $k, $v));
            }
        }

        if (isset(_Conf::$conf["debug"]["switch"]) && _Conf::$conf["debug"]["switch"]) {
            $this->context->data_system = $this->context->data_system ?: new \stdClass();
            echo _Json::encode($this->context);
            _Log::debug($this->context);
            return;
        }

        $res = [];
        $res["code"] = $this->context->code;
        $res["message"] = $this->context->message;
        $res["data"] = $this->context->data;
        $res["time"] = $this->context->time;
        $res["consume"] = $this->context->consume;
        $res["guid"] = $this->context->guid;
        $res["rank"] = $this->context->rank;
        echo _Json::encode($res);
        _Log::response($res);

    }

}
