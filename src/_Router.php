<?php

namespace Junyang7\PhpCommon;

class _Router
{

    public static function any($rule, $call)
    {

        self::methodList(["ANY",], $rule, $call);

    }

    public static function get($rule, $call)
    {

        self::methodList(["GET",], $rule, $call);

    }

    public static function post($rule, $call)
    {

        self::methodList(["POST",], $rule, $call);

    }

    public static function put($rule, $call)
    {

        self::methodList(["PUT",], $rule, $call);

    }

    public static function delete($rule, $call)
    {

        self::methodList(["DELETE",], $rule, $call);

    }

    public static function options($rule, $call)
    {

        self::methodList(["OPTIONS",], $rule, $call);

    }

    public static function head($rule, $call)
    {

        self::methodList(["HEAD",], $rule, $call);

    }

    public static function patch($rule, $call)
    {

        self::methodList(["PATCH",], $rule, $call);

    }

    public static function method($method, $rule, $call)
    {

        self::methodList([$method,], $rule, $call);

    }

    public static function methodList($method_list, $rule, $call)
    {

        $r = new _RouterInstance();
        $r->methodList($method_list, $rule, $call);

    }

    public static function middleware($middleware)
    {

        return self::middlewareList([$middleware,]);

    }

    public static function middlewareList($middleware_list)
    {

        $r = new _RouterInstance();
        $r->middlewareList($middleware_list);
        return $r;

    }

    public static function prefix($prefix)
    {

        $r = new _RouterInstance();
        $r->prefix($prefix);
        return $r;

    }

    public static function group($group)
    {

        $group();

    }

}
