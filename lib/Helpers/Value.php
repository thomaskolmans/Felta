<?php
namespace lib\Helpers;

class Value{

    public $value;

    function __construct($value){
        $this->value = $value;
    }
    public function is($type){
        switch(strtolower($type)){
            case "number":
                $this->isNumber();
            break;
            case "string":
                $this->isString();
            break;
            case "boolean":
                $this->isBoolean();
            break;
            case "array":
                $this->isArray();
            break;
            case "json":
                $this->isJson();
            break;
            case "email":
                $this->isEmail();
            break;
            case "ip":
                $this->isIp();
            break;
            case "file":
                $this->isFile();
            break;
            case "set":
                $this->isSet();
            break;
        }
    }
    public function isNumber(){
        return is_numeric($this->value);
    }
    public function isString(){
        return is_string($this->value);
    }
    public function isBoolean(){
        return is_bool($this->value);
    }
    public function isArray(){
        return is_array($this->value);
    }
    public function isJson(){
        if(in_array($this->value[0], array("{","["))){
            json_decode($this->value);
            return (json_last_error() == JSON_ERROR_NONE);
        }
        return false;
    }
    public function isEmail(){
        return filter_var($this->value, FILTER_VALIDATE_EMAIL);
    }
    public function isIp(){
        return filter_var($this->value, FILTER_VALIDATE_IP);
    }
    public function isFile(){
        return is_file($this->value);
    }
    public function isSet(){
        return isset($this->value);
    }
    public function setValue($value){
        $this->value = $value;
        return $this;
    }
    public function getValue(){
        return $this->value;
    }
    public function __toString(){
        return (string) $this->value;
    }
}