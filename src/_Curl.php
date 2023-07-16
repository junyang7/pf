<?php

namespace Junyang7\PhpCommon;

class _Curl
{

    public $ssl_verify_host = false;
    public $verify_peer = false;
    public $header_out = false;
    public $follow_location = true;
    public $no_signal = true;
    public $return_transfer = true;
    public $verbose = false;
    public $header = false;
    public $no_body = false;
    public $connect_timeout_ms = 200;
    public $timeout_ms = 200;
    public $customer_request = "GET";
    public $url = "";
    public $post_fields;
    public $http_header = [];
    public $cookie = "";
    public $user_agent = "";
    public $proxy = "";
    public $proxy_port = 0;
    public $ca_info = "";

    public function request()
    {

        $ch = curl_init();
        curl_setopt($ch, CURLINFO_HEADER_OUT, $this->header_out);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $this->follow_location);
        curl_setopt($ch, CURLOPT_NOSIGNAL, $this->no_signal);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $this->connect_timeout_ms);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $this->timeout_ms);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $this->return_transfer);
        curl_setopt($ch, CURLOPT_VERBOSE, $this->verbose);
        curl_setopt($ch, CURLOPT_HEADER, $this->header);
        curl_setopt($ch, CURLOPT_NOBODY, $this->no_body);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->customer_request);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->http_header);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
        curl_setopt($ch, CURLOPT_COOKIE, $this->cookie);
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->post_fields);
        curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
        curl_setopt($ch, CURLOPT_PROXYPORT, $this->proxy_port);
        curl_setopt($ch, CURLOPT_CAINFO, $this->ca_info);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verify_peer);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $this->ssl_verify_host);
        $data = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        if (0 != $errno) {
            throw new \Exception(sprintf("Curl执行异常|%s|%s", $errno, $error));
        }
        $res = [];
        $res["data"] = $data;
        $res["info"] = $info;
        return $res;

    }

}
