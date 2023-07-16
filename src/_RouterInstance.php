<?php

namespace Junyang7\PhpCommon;

class _RouterInstance
{

    private $prefix = "";
    private $middleware_list = [];
    private $method_list = [];
    private static $group_list = [];

    public function any($rule, $call)
    {

        self::methodList(["ANY",], $rule, $call);

    }

    public function get($rule, $call)
    {

        self::methodList(["GET",], $rule, $call);

    }

    public function post($rule, $call)
    {

        self::methodList(["POST",], $rule, $call);

    }

    public function put($rule, $call)
    {

        self::methodList(["PUT",], $rule, $call);

    }

    public function delete($rule, $call)
    {

        self::methodList(["DELETE",], $rule, $call);

    }

    public function options($rule, $call)
    {

        self::methodList(["OPTIONS",], $rule, $call);

    }

    public function head($rule, $call)
    {

        self::methodList(["HEAD",], $rule, $call);

    }

    public function patch($rule, $call)
    {

        self::methodList(["PATCH",], $rule, $call);

    }

    public function method($method, $rule, $call)
    {

        self::methodList([$method,], $rule, $call);

    }

    public function methodList($method_list, $rule, $call)
    {

        $group_prefix = "";
        $group_method_list = [];
        $group_middleware_list = [];
        foreach (self::$group_list as $group) {
            $group_prefix .= $group->prefix;
            array_push($group_method_list, "", ...$group->method_list);
            array_push($group_middleware_list, "", ...$group->middleware_list);
        }
        array_push($this->method_list, "", ...$method_list);
        $router = [
            "call" => $call,
            "method_list" => $method_list,
            "middleware_list" => [],
            "parameter_list" => [],
            "regexp" => false,
        ];
        array_push($router["method_list"], "", ...$method_list, ...$this->method_list, ...$group_method_list);
        array_push($router["middleware_list"], "", ...$this->middleware_list, ...$group_middleware_list);
        $rule_part_list = explode("/", $group_prefix . $rule);
        $formatted_rule_part_list = [];
        foreach ($rule_part_list as $rule_part) {
            if (!empty($rule_part) && ":" == $rule_part[0]) {
                if (1 == preg_match("/:([\w]+)(.*)/", $rule_part, $matched)) {
                    $router["regexp"] = true;
                    $router["parameter_list"][] = $matched[1];
                    $formatted_rule_part_list[] = $matched[2] ?: "([^\/]+)";
                    continue;
                }
            }
            $formatted_rule_part_list[] = $rule_part;
        }
        if ($router["regexp"]) {
            $router["rule"] = "/^" . implode("\/", $formatted_rule_part_list) . "$/";
        } else {
            $router["rule"] = implode("/", $formatted_rule_part_list);
        }
        $router["method_list"] = array_filter(array_unique($router["method_list"]));
        $router["middleware_list"] = array_filter(array_unique($router["middleware_list"]));
        $_ENV["_pf"]["router"][$router["rule"]] = $router;

    }

    public function middleware($middleware)
    {

        return self::middlewareList([$middleware,]);

    }

    public function middlewareList($middleware_list)
    {

        if (!empty($middleware_list)) {
            array_push($this->middleware_list, ...$middleware_list);
        }
        return $this;

    }

    public function prefix($prefix)
    {

        $this->prefix .= $prefix;
        return $this;

    }

    public function group($group)
    {

        array_push(self::$group_list, $this);
        $group();
        array_pop(self::$group_list);

    }

}
