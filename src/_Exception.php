<?php

namespace Junyang7\PhpCommon;

class _Exception extends \Exception
{

    public $data;

    public function __construct($message = "", $data = null, $code = -1)
    {

        parent::__construct();
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;

    }

}
