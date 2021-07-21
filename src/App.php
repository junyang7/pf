<?php


namespace Pf;


class App
{


    public function run($base_dir)
    {

        if(!is_string($base_dir) || empty($base_dir))
        {
            throw new \Exception('App的run方法必须传入一个字符串路径参数', -1);
        }

        echo '<pre>';
        \Pf\Core\Request::getInstance()->resolve($base_dir);

    }


}
