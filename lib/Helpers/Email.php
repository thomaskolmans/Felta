<?php
namespace lib\Helpers;

class Email{

    public $to;
    public $subject;
    public $message;
    public $headers;

    public $from;
    public $reply_to;

    public $succes = false;

    public function __construct($to = "",$subject = "",$message = "",$headers = ""){
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
        $this->headers = $headers;
    }
    public function load($file){
        if(file_exists($file)){
            $content = file_get_contents($file);
        }
        return $content;
    }
    public function html($argument){
        if($argument){
            $this->html = true;
            $this->headers .= "MIME-Version: 1.0\r\n";
            $this->headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        }else{
            $this->html = false;
        }
    }
    public function setTo($to){
        $this->to = $to;
        return $this;
    }
    public function setSubject($subject){
        $this->subject = $subject;
        return $this;
    }
    public function setMessage($message){
        $this->message = $message;
        return $this;
    }
    public function addMessage($message){
        $this->message .= $message;
        return $this;
    }
    public function setHeaders($headers){
        $this->headers = $headers;
        return $this;
    }
    public function addHeaders($headers){
        $this->headers .= $headers;
        return $this;
    }
    public function isEmail($value){
        $value = new Value($value);
        return $value->is("email");
    }
    public function send(){
        $this->succes = mail($this->to,$this->subject, $this->message,$this->headers);
        return $this;
    }
}
?>