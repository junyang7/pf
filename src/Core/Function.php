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


function I($ok)
{

    if(!$ok)
    {
        throw new \Pf\Core\PfException(-1, 'info', 'data');
    }

}


function V($view, $args = [])
{

    return \Pf\Core\View::render($view, $args);

}
