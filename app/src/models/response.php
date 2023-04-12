<?php

namespace src\models;

class Response {

    private string $code;
    private string $msg;
    private null|string $errorMsg;
    private object|array $body;
    public function __construct($code, $msg, $body, $errorMsg){
        $this->code = $code;
        $this->msg = $msg;
        $this->body = $body;
        $this->errorMsg = $errorMsg;
    }
    public function getCode(){
        return $this->code;
    }
    public function getMsg(){
        return $this->msg;
    }
    public function getErrorMsg(){
        return $this->errorMsg;
    }
    public function jsonSerialize() {
        return get_object_vars($this);
    }
}