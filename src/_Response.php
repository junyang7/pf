<?php

namespace Junyang7\PhpCommon;

class _Response
{

    public $code = 0;
    public $message = "success";
    public $data = null;
    public $time = 0;
    public $consume = 0;
    public $request_id = "";
    public $request_sn = 0;
    public $file = "";
    public $line = 0;
    public $type = "success";
    public $call = [];

    public function __construct()
    {

        $_ENV["_pf"]["runtime"]["time_e"] = microtime(true);
        $this->time = intval($_ENV["_pf"]["runtime"]["time_e"]);
        $this->consume = intval(($_ENV["_pf"]["runtime"]["time_e"] - $_ENV["_pf"]["runtime"]["time_s"]) * 1000);
        $this->request_id = $_SERVER["HTTP_PF_REQUEST_ID"];
        $this->request_sn = $_SERVER["HTTP_PF_REQUEST_SN"];

    }

    public function JSON()
    {

        if (empty($this->data)) {
            $this->data = new \stdClass();
        }
        if (0 == $this->code) {
            $res = [
                "code" => $this->code,
                "message" => $this->message,
                "data" => $this->data,
                "time" => $this->time,
                "consume" => $this->consume,
                "request_id" => $this->request_id,
                "request_sn" => $this->request_sn,
            ];
            _Log::success($res);
        } else {
            $res = [
                "code" => $this->code,
                "message" => $this->message,
                "data" => $this->data,
                "time" => $this->time,
                "consume" => $this->consume,
                "request_id" => $this->request_id,
                "request_sn" => $this->request_sn,
                "file" => $this->file,
                "line" => $this->line,
                "type" => $this->type,
                "call" => $this->call,
            ];
            _Log::write($this->type, $res);
            if (!C("debug.enable", false)) {
                unset($res["file"], $res["line"], $res["type"], $res["call"]);
            }
        }
        $header_map = $_ENV["_pf"]["conf"]["header"] ?? [];
        $header_map["content-type"] = "application/json";
        foreach ($header_map as $k => $v) {
            header(sprintf("%s: %s", $k, $v));
        }
        echo _Json::encode($res);

    }

    public function HTML()
    {

        if (0 == $this->code) {
            $res = [
                "code" => $this->code,
                "message" => $this->message,
                "data" => $this->data,
                "time" => $this->time,
                "consume" => $this->consume,
                "request_id" => $this->request_id,
                "request_sn" => $this->request_sn,
            ];
            _Log::success($res);
        } else {
            $res = [
                "code" => $this->code,
                "message" => $this->message,
                "data" => $this->data,
                "time" => $this->time,
                "consume" => $this->consume,
                "request_id" => $this->request_id,
                "request_sn" => $this->request_sn,
                "file" => $this->file,
                "line" => $this->line,
                "type" => $this->type,
                "call" => $this->call,
            ];
            _Log::write($this->type, $res);
            if (!C("debug.enable", false)) {
                unset($res["file"], $res["line"], $res["type"], $res["call"]);
            }
        }
        $header_map = $_ENV["_pf"]["conf"]["header"] ?? [];
        $header_map["content-type"] = "text/html";
        foreach ($header_map as $k => $v) {
            header(sprintf("%s: %s", $k, $v));
        }
        if (0 == $res["code"]) {
            if (!is_null($res["data"]) && is_scalar($res["data"])) {
                echo $res["data"];
            }
        } else {
            if (is_scalar($res["message"]) && "" !== $res["message"]) {
                echo $res["message"];
            }
        }

    }

}
