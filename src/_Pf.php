<?php

namespace Junyang7\PhpCommon;

class _Pf
{

    private $ctx;

    public function run($wd)
    {

        $_ENV["_pf"]["runtime"]["time_s"] = microtime(true);
        if (isset($_SERVER["REQUEST_METHOD"]) && "OPTIONS" == $_SERVER["REQUEST_METHOD"]) {
            return;
        }
        $this->ctx = new _Context();
        $_ENV["_pf"]["runtime"]["wd"] = $wd;
        if (!isset($_SERVER["HTTP_PF_REQUEST_ID"]) || !isset($_SERVER["HTTP_PF_REQUEST_SN"])) {
            $_SERVER["HTTP_PF_REQUEST_ID"] = _Uuid::get();
            $_SERVER["HTTP_PF_REQUEST_SN"] = 0;
        }
        $_SERVER["HTTP_PF_REQUEST_SN"]++;
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set("display_errors", "Off");
        $this->registerException();
        $this->registerHelper();
        $this->registerConf();
        $this->registerIni();
        $this->registerMode();
        if ("cli" == $_ENV["_pf"]["runtime"]["mode"]) {
            return;
        }
        $request = [
            "method" => $_SERVER["REQUEST_METHOD"],
            "uri" => $_SERVER["REQUEST_URI"],
            "request" => $_REQUEST,
        ];
        _Log::request($request);
        $this->checkOrigin();
        $this->registerRouter();
        $this->checkRouter();
        $this->checkRouterMethod();
        $this->middlewareBefore();
        $this->business();
        $this->middlewareAfter();

    }

    private function registerException()
    {

        register_shutdown_function(
            function () {
                if ($error = error_get_last()) {
                    $response = new _Response();
                    $response->code = -1;
                    $response->message = $error["message"];
                    $response->file = $error["file"];
                    $response->line = $error["line"];
                    $response->type = "shutdown";
                    $response->JSON();
                    exit();
                }
            }
        );
        set_error_handler(
            function ($code, $message, $file, $line) {
                $response = new _Response();
                $response->code = -1;
                $response->message = $message;
                $response->file = $file;
                $response->line = $line;
                $response->type = "error";
                $response->JSON();
                exit();
            }
        );
        set_exception_handler(
            function ($exception) {
                $response = new _Response();
                $response->code = -1;
                $response->message = $exception->getMessage();
                $response->file = $exception->getFile();
                $response->line = $exception->getLine();
                $response->call = $exception->getTrace();
                $response->type = "exception";
                if ($exception instanceof _Exception) {
                    $response->code = $exception->getCode();
                    $response->data = $exception->data;
                }
                $response->JSON();
                exit();
            }
        );

    }

    private function registerHelper()
    {

        require "_Function.php";

    }

    private function registerConf()
    {

        try {
            $_ENV["_pf"]["runtime"]["env"] = trim(_File::read($_ENV["_pf"]["runtime"]["wd"] . "/conf/env/env"));
        } catch (\Exception $exception) {
            $_ENV["_pf"]["runtime"]["env"] = "dev";
        }
        $common = [];
        $common_dir = $_ENV["_pf"]["runtime"]["wd"] . "/conf/common";
        if (_Directory::exists($common_dir)) {
            $common_file_list = _Directory::read($_ENV["_pf"]["runtime"]["wd"] . "/conf/common");
            foreach ($common_file_list as $common_file) {
                $path_info = pathinfo($common_file);
                if (is_array($content = require_once $common_file)) {
                    $common[$path_info["filename"]] = $content;
                }
            }
        }
        $env = [];
        $env_dir = $_ENV["_pf"]["runtime"]["wd"] . "/conf/env/" . $_ENV["_pf"]["runtime"]["env"];
        if (_Directory::exists($env_dir)) {
            $env_file_list = _Directory::read($_ENV["_pf"]["runtime"]["wd"] . "/conf/env/" . $_ENV["_pf"]["runtime"]["env"]);
            foreach ($env_file_list as $env_file) {
                $path_info = pathinfo($env_file);
                if (is_array($content = require_once $env_file)) {
                    $env[$path_info["filename"]] = $content;
                }
            }
        }
        $env_base = $env["base"] ?? [];
        unset($env["base"]);
        $common_base = $common["base"] ?? [];
        unset($common["base"]);
        $_ENV["_pf"]["conf"] = _Array::merge(_Array::merge($common_base, $common), _Array::merge($env_base, $env));

    }

    private function registerIni()
    {

        if (!isset($_ENV["_pf"]["conf"]["ini"])) {
            return;
        }
        if (empty($_ENV["_pf"]["conf"]["ini"])) {
            return;
        }
        foreach ($_ENV["_pf"]["conf"]["ini"] as $ini_k => $ini_v) {
            ini_set($ini_k, $ini_v);
        }

    }

    private function registerMode()
    {

        if ("cli" == php_sapi_name()) {
            $_ENV["_pf"]["runtime"]["mode"] = "cli";
            return;
        }
        $_ENV["_pf"]["runtime"]["method"] = $_SERVER["REQUEST_METHOD"];
        $_ENV["_pf"]["runtime"]["path"] = parse_url($_SERVER["REQUEST_URI"])["path"];
        $uri_length = strlen($_ENV["_pf"]["runtime"]["path"]);
        if ($uri_length == 4 && "/api" == $_ENV["_pf"]["runtime"]["path"] || $uri_length >= 5 && _String::hasPrefix($_ENV["_pf"]["runtime"]["path"], "/api/")) {
            $_ENV["_pf"]["runtime"]["mode"] = "api";
            return;
        }
        $_ENV["_pf"]["runtime"]["mode"] = "web";

    }

    private function checkOrigin()
    {

        if (!isset($_SERVER["HTTP_ORIGIN"])) {
            return;
        }
        $http_origin = $_SERVER["HTTP_ORIGIN"];
        if (1 != preg_match("/([^:]+):\/\/([^:]+):?(\d+)?/", $http_origin, $matched)) {
            return;
        }
        if (isset($_ENV["_pf"]["conf"]["origin"])) {
            $origin_list = $_ENV["_pf"]["conf"]["origin"];
            foreach ($origin_list as $origin) {
                if ("*" == $origin || $matched[2] == $origin || "." == $origin[0] && _String::hasSuffix($matched[2], $origin)) {
                    $_ENV["_pf"]["conf"]["header"]["access-control-allow-origin"] = $origin;
                    return;
                }
            }
        }
        throw new \Exception("跨域阻止");

    }

    private function registerRouter()
    {

        $router_dir = $_ENV["_pf"]["runtime"]["wd"] . "/router";
        if (!_Directory::exists($router_dir)) {
            return;
        }
        $router_file_list = _Directory::read($router_dir);
        foreach ($router_file_list as $router_file) {
            require_once $router_file;
        }

    }

    private function checkRouter()
    {

        if (!empty($_ENV["_pf"]["router"])) {
            if (isset($_ENV["_pf"]["router"][$_ENV["_pf"]["runtime"]["path"]])) {
                $_ENV["_pf"]["runtime"]["router"] = $_ENV["_pf"]["router"][$_ENV["_pf"]["runtime"]["path"]];
                return;
            }
            foreach ($_ENV["_pf"]["router"] as $router) {
                if ($router["regexp"]) {
                    if (1 == preg_match($router["rule"], $_ENV["_pf"]["runtime"]["path"], $matched)) {
                        array_shift($matched);
                        foreach ($router["parameter_list"] as $index => $parameter) {
                            $_GET[$parameter] = $_REQUEST[$parameter] = $matched[$index];
                        }
                        $_ENV["_pf"]["runtime"]["router"] = $router;
                        return;
                    }
                }
            }
            if (isset($_ENV["_pf"]["conf"]["router"]["auto"]["switch"]) && $_ENV["_pf"]["conf"]["router"]["auto"]["switch"]) {
                $part_list = explode("/", trim($_ENV["_pf"]["runtime"]["path"], "/"));
                foreach ($part_list as &$part) {
                    $part = ucfirst($part);
                }
                $controller = $_ENV["_pf"]["conf"]["router"]["auto"]["namespace"] . "\\" . implode("\\", $part_list);
                $action = strtolower($_ENV["_pf"]["runtime"]["method"]);
                if (class_exists($controller) && method_exists($controller, $action)) {
                    $_ENV["_pf"]["runtime"]["router"] = [
                        "rule" => $_ENV["_pf"]["runtime"]["path"],
                        "call" => [new $controller(), $action,],
                        "method_list" => [$_ENV["_pf"]["runtime"]["method"],],
                        "middleware_list" => [],
                        "parameter_list" => [],
                        "regexp" => false,
                    ];
                    return;
                }
            }
        }
        throw new \Exception("路由不存在");

    }

    private function checkRouterMethod()
    {

        if (!isset($_ENV["_pf"]["conf"]["router"]["method"]["check"]) || !$_ENV["_pf"]["conf"]["router"]["method"]["check"] || empty($_ENV["_pf"]["conf"]["router"]["method"]["allow_list"])) {
            return;
        }
        if (!in_array($_ENV["_pf"]["runtime"]["method"], $_ENV["_pf"]["conf"]["router"]["method"]["allow_list"])) {
            throw new \Exception("不支持的请求方法");
        }

    }

    private function middlewareBefore()
    {

        foreach ($_ENV["_pf"]["runtime"]["router"]["middleware_list"] as $middleware) {
            if (!class_exists($middleware)) {
                throw new \Exception("中间件不存在");
            }
            if (method_exists($middleware, "before")) {
                call_user_func([$middleware, "before",], $this->ctx);
            }
        }

    }

    private function business()
    {

        call_user_func($_ENV["_pf"]["runtime"]["router"]["call"], $this->ctx);

    }

    private function middlewareAfter()
    {

        foreach ($_ENV["_pf"]["runtime"]["router"]["middleware_list"] as $middleware) {
            if (!class_exists($middleware)) {
                throw new \Exception("中间件不存在");
            }
            if (method_exists($middleware, "after")) {
                call_user_func([$middleware, "after",], $this->ctx);
            }
        }

    }

}
