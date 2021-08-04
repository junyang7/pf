<?php


function C($name, $default = NULL)
{

    $name_list = explode('.', $name);
    $conf = \Pf\App::getInstance()->conf_list;

    foreach($name_list as $name)
    {

        if(!isset($conf[$name]))
        {
            return $default;
        }

        $conf = $conf[$name];

    }

    return $conf;

}


function I($ok, $error = -1, $data = [])
{

    if($ok)
    {
        return;
    }

    if(is_int($error))
    {

        $code = $error;
        $info = C('error.' . $code);

        if(empty($info))
        {
            $info = '系统繁忙，稍后重试';
        }

    }
    else
    {

        $code = -1;
        $info = $error;

    }

    throw new \Pf\Core\PfException($code, $info, $data);

}


function V($view, $args = [])
{

    return \Pf\Core\View::render(str_replace('.', DIRECTORY_SEPARATOR, $view), $args);

}
