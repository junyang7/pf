<?php


namespace Pf\Core;


class Route
{


    const NAMESPACE = '\App\Controller\\';
    const EXTEND = '.php';
    const LENGTH = 4;
    const PATH = _PF_DIR . DIRECTORY_SEPARATOR . 'route' . DIRECTORY_SEPARATOR;
    const RULE = '/^(\w+)@(\w+)$/';
    const PREFIX = '/';
    const METHOD_LIST = ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH', 'CLI', ];


    private static $route_list = [];
    private static $instance;
    private static $method_list = [];
    private static $uri = '';
    private static $rule = '';
    private static $extend_list = [];
    private static $namespace = self::NAMESPACE;
    private static $prefix = '';
    private static $middleware_list = [];


    public static function register()
    {

        $file_part_list = Dir::getFileList(self::PATH, 0, self::EXTEND, self::LENGTH, TRUE);

        foreach($file_part_list as $file_part)
        {

            ksort($file_part);

            foreach($file_part as $file => $path)
            {
                require_once $path;
            }

        }

        define('_PF_APP_ROUTE_LIST', self::$route_list);

    }


    private function __construct()
    {

    }


    private function __clone()
    {

    }


    private static function getInstance()
    {

        if(!self::$instance instanceof self)
        {
            self::$instance = new self();
        }

        return self::$instance;

    }


    public static function get($uri, $rule, $extend_list = [])
    {

        self::$method_list = ['GET', ];
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


    public static function post($uri, $rule, $extend_list = [])
    {

        self::$method_list = ['POST', ];
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


    public static function delete($uri, $rule, $extend_list = [])
    {

        self::$method_list = ['DELETE', ];
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


    public static function options($uri, $rule, $extend_list = [])
    {

        self::$method_list = ['OPTIONS', ];
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


    public static function cli($uri, $rule, $extend_list = [])
    {

        self::$method_list = ['CLI', ];
        self::$uri = $uri;
        self::$rule = $rule;
        self::$extend_list = $extend_list;
        self::add();

    }


    public static function any($uri, $rule, $extend_list = [])
    {

        self::$method_list = self::METHOD_LIST;
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

            if(!in_array($method, self::METHOD_LIST))
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


    public static function prefix($prefix)
    {

        if(!is_string($prefix) || empty($prefix))
        {
            throw new PfException(-1, '路由前缀格式错误', ['prefix' => self::$prefix, ]);
        }

        $prefix = trim($prefix, "\0\t\n\x0B\r /");

        if(empty($prefix))
        {
            self::$prefix = self::PREFIX;
            return self::getInstance();
        }

        self::$prefix = self::PREFIX . $prefix . self::PREFIX;
        return self::getInstance();

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

        if($namespace[0] == '\\')
        {
            self::$namespace = $namespace . '\\';
            return self::getInstance();
        }

        self::$namespace = self::NAMESPACE . $namespace . '\\';
        return self::getInstance();

    }


    public static function group($group)
    {

        if(!is_callable($group) || get_class($group) != 'Closure')
        {
            throw new PfException(-1, '路由组格式错误', ['group' => $group, ]);
        }

        $group();
        self::$method_list = [];
        self::$uri = '';
        self::$rule = '';
        self::$extend_list = [];
        self::$prefix = '';
        self::$middleware_list = [];
        self::$namespace = '';

    }


    public static function add()
    {

        if(!is_string(self::$uri) || empty(self::$uri))
        {
            throw new PfException(-1, '路由请求路径格式错误', ['uri' => self::$uri, ]);
        }

        if(!is_string(self::$rule) || empty(self::$rule) || preg_match(self::RULE, self::$rule, $match) != 1)
        {
            throw new PfException(-1, '路由解析规则格式错误', ['rule' => self::$rule, ]);
        }

        self::$route_list[self::$prefix . self::$uri] = [
            'method_list' => self::$method_list,
            'extend_list' => self::$extend_list,
            'middleware_list' => self::$middleware_list,
            'controller' => self::$namespace . $match[1],
            'action' => $match[2],
        ];

    }


}
