<?php


namespace Pf;


class App
{


    public function __construct($base_dir)
    {

        \Pf\Core\Runtime::register($base_dir);
        \Pf\Core\Exception::register();
        \Pf\Core\Shutdown::register();
        \Pf\Core\Error::register();
        \Pf\Core\Env::register();
        \Pf\Core\Conf::register();
        \Pf\Core\Helper::register();
        \Pf\Core\Route::register();
        \Pf\Core\Table::register();

    }


    public function run()
    {

        $request = new \Pf\Core\Request();
        $response = call_user_func([new $request->route['controller'](), $request->route['action'], ], $request);
        echo '<pre>';
        var_dump($response);

    }


}
