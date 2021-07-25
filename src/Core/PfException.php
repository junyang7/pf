<?php


namespace Pf\Core;


class PfException extends \Exception
{


    protected $_code = -1;
    protected $_info = '';
    protected $_data = [];


    public function __construct($code, $info = '系统繁忙，稍后重试', $data = [])
    {

        parent::__construct();

        $this->_code = $code;
        $this->_info = $info;
        $this->_data = $data;

    }


    public function getPfCode()
    {

        return $this->_code;

    }


    public function getPfInfo()
    {

        return $this->_info;

    }


    public function getPfData()
    {

        return $this->_data;

    }


}
