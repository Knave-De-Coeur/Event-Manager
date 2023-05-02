<?php

namespace Src\Models;

class Response {

    private $code;
    private $msg;
    private $errorMsg;
    private $body;
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