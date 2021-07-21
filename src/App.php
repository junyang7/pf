<?php


namespace Pf;


use Pf\Core\PfException;


class App
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

        if(!self::$instance instanceof self)
        {
            self::$instance = new self();
        }

        return self::$instance;

    }


    public $path_dir_base;
    public $env_cli = 'cli';
    public $env_api = 'api';
    public $env_web = 'web';
    public $env = '';
    public $path_file_env = '';
    public $conf_list = [];
    public $path_dir_common;
    public $extend_conf;
    public $length_extend_conf;
    public $path_dir_env;
    public $path_dir_route;
    public $extend_route;
    public $length_extend_route;
    public $support_method_list;
    public $controller_namespace;
    public $uri_prefix;
    public $rule_pattern;
    public $router_list = [];
    public $path_dir_model;
    public $extend_model;
    public $template_model;
    public $router;
    public $uri = '';
    public $method = '';
    public $context = [];
    public $response = NULL;


    public function run($base_dir)
    {

        $this->_runtime($base_dir);
        $this->_exception();
        $this->_shutdown();
        $this->_error();
        $this->_env();
        $this->_helper();
        $this->_conf();
        $this->_route();
        $this->_table();
        $this->_origin();
        $this->_uri();
        $this->_method();
        $this->_middlewareBefore();
        $this->_business();
        $this->_middlewareAfter();
        $this->_render();

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


    private function _runtime($base_dir)
    {

        if(!is_string($base_dir) || empty($base_dir))
        {
            throw new \Exception('App的run方法必须传入一个字符串路径参数', -1);
        }

        $this->path_dir_base = $base_dir;

        \Pf\Core\Runtime::register($this);

    }
    private function _exception()
    {

        \Pf\Core\Exception::register();

    }
    private function _shutdown()
    {

        \Pf\Core\Shutdown::register();

    }
    private function _error()
    {

        \Pf\Core\Error::register();

    }
    private function _env()
    {

        $this->path_file_env = $this->path_dir_base . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR . 'env.php';

        \Pf\Core\Env::register($this);

    }
    private function _helper()
    {

        \Pf\Core\Helper::register();

    }
    private function _conf()
    {

        $this->path_dir_common = $this->path_dir_base . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR;
        $this->extend_conf = '.php';
        $this->length_extend_conf = 4;
        $this->path_dir_env = $this->path_dir_base . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR . 'env' . DIRECTORY_SEPARATOR;

        \Pf\Core\Conf::register($this);

    }
    private function _route()
    {

        $this->path_dir_route = $this->path_dir_base . DIRECTORY_SEPARATOR . 'route' . DIRECTORY_SEPARATOR;
        $this->extend_route = '.php';
        $this->length_extend_route = 4;
        $this->support_method_list =['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH', 'CLI', ];
        $this->controller_namespace = '\App\Controller\\';
        $this->uri_prefix = '/';
        $this->rule_pattern = '/^(\w+)@(\w+)$/';

        \Pf\Core\Route::register();

    }
    private function _table()
    {

        $this->path_dir_model = $this->path_dir_base . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR;
        $this->extend_model = '.php';
        $this->template_model = <<<'TEMPLATE'
<?php


namespace App\Model;


class %s extends \Pf\Core\Model
{


    private static $instance = NULL;
    
    
    public function __construct()
    {
    
        parent::__construct('%s');
        
    }
    
    
    public static function getInstance()
    {
    
        if(!isset(self::$instance))
        {
            self::$instance = new self();
        }
        
        return self::$instance;
        
    }
    
    
}

TEMPLATE;

        \Pf\Core\Table::register($this);

    }
    private function _origin()
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
    private function _uri()
    {

        if($this->env == $this->env_cli)
        {

            if($this->cli('argc') < 2)
            {
                throw new PfException(-1, '参数错误', ['argc' => $this->cli('argc'), ]);
            }

            $this->method = strtoupper($this->env_cli);
            $this->uri = $this->cli('argv')[1];

        }
        else
        {

            if(empty($this->uri = $this->server('REQUEST_URI')))
            {
                throw new PfException(-1, '参数错误', ['uri' => $this->uri, ]);
            }

            if(empty($this->method = $this->server('REQUEST_METHOD')))
            {
                throw new PfException(-1, '参数错误', ['method' => $this->method, ]);
            }

        }

        if(isset($this->router_list[$this->uri]))
        {
            $this->router = $this->router_list[$this->uri];
        }
        else
        {

            foreach($this->router_list as $uri => $router)
            {

                if(($uri_length = strlen($uri)) < 2 || $uri[0] != '/' || $uri[$uri_length - 1] != '/')
                {
                    continue;
                }

                foreach($router['extend_list'] as $parameter => $pattern)
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

                    foreach($router['extend_list'] as $parameter => $pattern)
                    {
                        $this->context[$parameter] = $match[$i++];
                    }

                    $this->router = $router;
                    break;

                }

            }

        }

        if(empty($this->router))
        {
            throw new PfException(-1, '路由未定义', ['uri' => $this->uri, ]);
        }

    }
    private function _method()
    {

        if($this->method == 'OPTIONS')
        {
            exit();
        }

        if(!in_array($this->method, $this->router['method_list']))
        {
            throw new PfException(-1, '请求方法不允许', ['method' => $this->method, ]);
        }

    }
    private function _middlewareBefore()
    {

        \Pf\Core\Middleware::before($this);

    }
    private function _business()
    {

        $this->response = call_user_func([new $this->router['controller'](), $this->router['action'], ], $this);

    }
    private function _middlewareAfter()
    {

        \Pf\Core\Middleware::after($this);

    }
    private function _render()
    {

        \Pf\Core\Render::success($this->response);

    }
    private function _row($target, $name, $default)
    {

        if(!is_string($name) || empty($name))
        {
            throw new PfException(-1, '参数错误', ['name' => $name,]);
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
