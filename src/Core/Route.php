<?php


namespace Pf\Core;


class Route
{


    private static $instance;
    private static $method_list = [];
    private static $uri = '';
    private static $rule = '';
    private static $extend_list = [];
    private static $middleware_list = [];
    private static $namespace = '';
    private static $prefix = '';


    private function __construct()
    {

    }
    private function __clone()
    {

    }
    private static function initialize()
    {

        self::$method_list = [];
        self::$uri = '';
        self::$rule = '';
        self::$extend_list = [];
        self::$middleware_list = [];
        self::$namespace = \Pf\App::getInstance()->controller_namespace;
        self::$prefix = '';

    }
    private static function add()
    {

        if(!is_string(self::$rule) || empty(self::$rule) || preg_match(\Pf\App::getInstance()->rule_pattern, self::$rule, $match) != 1)
        {
            throw new PfException(-1, '路由解析规则格式错误', ['rule' => self::$rule, ]);
        }

        \Pf\App::getInstance()->router_list[self::getPrefix() . self::getUri()] = [
            'method_list' => self::getMethodList(),
            'extend_list' => self::getExtendList(),
            'middleware_list' => self::getMiddlewareList(),
            'controller' => self::getNamespace() . $match[1],
            'action' => $match[2],
        ];

    }


    private static function getMethodList()
    {

        return self::$method_list;

    }
    private static function getExtendList()
    {

        if(!is_array(self::$extend_list))
        {
            throw new PfException(-1, '参数格式错误', ['extend_list' => self::$extend_list, ]);
        }

        return self::$extend_list;

    }
    private static function getMiddlewareList()
    {

        return self::$middleware_list;

    }
    private static function getNamespace()
    {

        if(empty(self::$namespace))
        {
            return \Pf\App::getInstance()->controller_namespace;
        }

        return self::$namespace;

    }
    private static function getPrefix()
    {

        if(empty(self::$prefix))
        {
            return \Pf\App::getInstance()->uri_prefix;
        }

        return self::$prefix;

    }
    private static function getUri()
    {

        if(!is_string(self::$uri) || empty(self::$uri))
        {
            throw new PfException(-1, '路由请求路径格式错误', ['uri' => self::$uri, ]);
        }

        return trim(self::$uri, "\0\t\n\x0B\r /");

    }


    public static function getInstance()
    {

        if(!self::$instance instanceof self)
        {
            self::$instance = new self();
        }

        return self::$instance;

    }
    public static function register()
    {

        $app = \Pf\App::getInstance();
        $file_part_list = Dir::getFileList($app->path_dir_route, 0, $app->extend_route, $app->length_extend_route, TRUE);

        foreach($file_part_list as $file_part)
        {

            ksort($file_part);

            foreach($file_part as $file => $path)
            {
                require_once $path;
            }

        }

    }
    public static function post($uri, $rule, $extend_list = [])
    {

        self::$method_list = ['POST', ];
        self::$uri = $uri;
        self::$rule = $rule;
        self::$extend_list = $extend_list;
        self::add();

    }
    public static function delete($uri, $rule, $extend_list = [])
    {

        self::$method_list = ['DELETE', ];
        self::$uri = $uri;
        self::$rule = $rule;
        self::$extend_list = $extend_list;
        self::add();

    }
    public static function put($uri, $rule, $extend_list = [])
    {

        self::$method_list = ['PUT', ];
        self::$uri = $uri;
        self::$rule = $rule;
        self::$extend_list = $extend_list;
        self::add();

    }
    public static function get($uri, $rule, $extend_list = [])
    {

        self::$method_list = ['GET', ];
        self::$uri = $uri;
        self::$rule = $rule;
        self::$extend_list = $extend_list;
        self::add();

    }
    public static function cli($uri, $rule, $extend_list = [])
    {

        self::$method_list = ['CLI', ];
        self::$uri = $uri;
        self::$rule = $rule;
        self::$extend_list = $extend_list;
        self::add();

    }
    public static function options($uri, $rule, $extend_list = [])
    {

        self::$method_list = ['OPTIONS', ];
        self::$uri = $uri;
        self::$rule = $rule;
        self::$extend_list = $extend_list;
        self::add();

    }
    public static function head($uri, $rule, $extend_list = [])
    {

        self::$method_list = ['HEAD', ];
        self::$uri = $uri;
        self::$rule = $rule;
        self::$extend_list = $extend_list;
        self::add();

    }
    public static function connect($uri, $rule, $extend_list = [])
    {

        self::$method_list = ['CONNECT', ];
        self::$uri = $uri;
        self::$rule = $rule;
        self::$extend_list = $extend_list;
        self::add();

    }
    public static function trace($uri, $rule, $extend_list = [])
    {

        self::$method_list = ['TRACE', ];
        self::$uri = $uri;
        self::$rule = $rule;
        self::$extend_list = $extend_list;
        self::add();

    }
    public static function patch($uri, $rule, $extend_list = [])
    {

        self::$method_list = ['PATCH', ];
        self::$uri = $uri;
        self::$rule = $rule;
        self::$extend_list = $extend_list;
        self::add();

    }
    public static function any($uri, $rule, $extend_list = [])
    {

        self::$method_list = \Pf\App::getInstance()->support_method_list;
        self::$uri = $uri;
        self::$rule = $rule;
        self::$extend_list = $extend_list;
        self::add();

    }
    public static function method($method, $uri, $rule, $extend_list = [])
    {

        self::methodList([$method, ], $uri, $rule, $extend_list);

    }
    public static function methodList($method_list, $uri, $rule, $extend_list = [])
    {

        foreach($method_list as $method)
        {

            if(!in_array($method, \Pf\App::getInstance()->support_method_list))
            {
                throw new PfException(-1, '路由方法暂不支持', ['method' => $method, ]);
            }

        }

        self::$method_list = $method_list;
        self::$uri = $uri;
        self::$rule = $rule;
        self::$extend_list = $extend_list;
        self::add();

    }
    public static function middleware($middleware)
    {

        return self::middlewareList([$middleware, ]);

    }
    public static function middlewareList($middleware_list)
    {

        foreach($middleware_list as $middleware)
        {

            if(!is_string($middleware) || empty($middleware))
            {
                throw new PfException(-1, '中间件格式错误', [$middleware, ]);
            }

        }

        self::$middleware_list = $middleware_list;
        return self::getInstance();

    }
    public static function namespace($namespace)
    {

        if(!is_string($namespace) || empty($namespace))
        {
            throw new PfException(-1, '路由命名空间格式错误', ['namespace' => $namespace, ]);
        }

        $namespace = rtrim(trim($namespace), '\\');

        if(!empty($namespace))
        {

            if($namespace[0] == '\\')
            {
                self::$namespace = $namespace . '\\';
            }
            else
            {
                self::$namespace = \Pf\App::getInstance()->controller_namespace . $namespace . '\\';
            }

        }

        return self::getInstance();

    }
    public static function prefix($prefix)
    {

        if(!is_string($prefix) || empty($prefix))
        {
            throw new PfException(-1, '路由前缀格式错误', ['prefix' => self::$prefix, ]);
        }

        $prefix = trim($prefix, "\0\t\n\x0B\r /");

        if(!empty($prefix))
        {
            self::$prefix = \Pf\App::getInstance()->uri_prefix . $prefix . \Pf\App::getInstance()->uri_prefix;
        }

        return self::getInstance();

    }
    public static function group($group)
    {

        if(!is_callable($group) || get_class($group) != 'Closure')
        {
            throw new PfException(-1, '路由组格式错误', ['group' => $group, ]);
        }

        $group();

        self::initialize();


    }


}
