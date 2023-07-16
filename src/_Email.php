<?php

namespace Junyang7\PhpCommon;

use PHPMailer\PHPMailer\PHPMailer;

class _Email
{

    public $host = "";
    public $port = "";
    public $encryption = "";
    public $username = "";
    public $password = "";
    public $from = ["address" => "", "name" => "",];
    public $to = [];
    public $cc = [];
    public $attachment = [];
    public $subject = "";
    public $body = "";

    public function send()
    {

        $email = new PHPMailer(true);
        $email->CharSet = PHPMailer::CHARSET_UTF8;
        $email->isSMTP();
        $email->isHTML();
        $email->SMTPAuth = true;
        $email->Host = $this->host;
        $email->Username = $this->username;
        $email->Password = $this->password;
        $email->SMTPSecure = $this->encryption;
        $email->Port = $this->port;
        $email->setFrom($this->from["address"], $this->from["name"]);
        foreach ($this->to as $to) {
            $email->addAddress($to["address"], $to["name"]);
        }
        foreach ($this->cc as $cc) {
            $email->addCc($cc["address"], $cc["name"]);
        }
        foreach ($this->attachment as $attachment_path => $attachment_name) {
            $email->addAttachment($attachment_path, $attachment_name);
        }
        $email->Subject = $this->subject;
        $email->Body = $this->body;
        if (false === $email->send()) {
            throw new \Exception($email->ErrorInfo);
        }
        
    }

}
