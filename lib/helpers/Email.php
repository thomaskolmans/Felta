<?php
namespace lib\helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email{

    public $phpMail;

    public $success = false;

    public function __construct($to = "",$subject = "",$message = ""){
        $this->phpMail = new PHPMailer(true);
        
        $this->setTo($to);
        $this->setSubject($subject);
        $this->setMessage($message);
    }

    public function load($file){
        if(file_exists($file)){
            $content = file_get_contents($file);
        }
        return $content;
    }

    public function html($argument){
        $this->phpMail->isHTML($argument); 
    }
    public function setTo($to, $name = null){
        if (empty($to)) return;
        $this->phpMail->addAddress($to, $name);
    }

    public function setFrom($from, $name = null) {
        if (empty($from)) return;
        $this->phpMail->SetFrom($from, $name);
    }
    
    public function setSubject($subject){
        if (empty($subject)) return;
        $this->phpMail->Subject = $subject;
    }

    public function setMessage($message){
        if (empty($message)) return;
        $this->phpMail->Body = $message;
        return $this;
    }
    public function addMessage($message){
        $this->phpMail->Body .= $message;
        return $this;
    }

    public function isEmail($value){
        $value = new Value($value);
        return $value->is("email");
    }

    public function send(){
        $this->success = $this->phpMail->send();
        return $this;
    }

    public function setSMTP($host = "", $username = "", $password = "") {
        if(empty($host) || empty($username) || empty($password)) {
            $smtpConfig = \lib\Felta::getConfig("smtp");
            $host = $smtpConfig["host"];
            $username = $smtpConfig["username"];
            $password = $smtpConfig["password"];
            if(empty($host) || empty($username) || empty($password)) {
                return;
            }
        }
        $this->phpMail->isSMTP();
        $this->phpMail->SMTPDebug = 0;
        $this->phpMail->Host       = $host;
        $this->phpMail->SMTPAuth = true;
        $this->phpMail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $this->phpMail->Username   = $username;
        $this->phpMail->Password   = $password;
        $this->phpMail->SMTPSecure = 'tls';
        $this->phpMail->Port       = 587;                  
    }
}
?>