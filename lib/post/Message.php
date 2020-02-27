<?php
namespace lib\post;

use lib\Felta;
use lib\helpers\Email;

class Message extends Post{

    protected $name = "post_message";
    protected $structure = [
        "id" => "int auto_increment",
        "title" => "varchar(255)",
        "message" => "longtext",
        "url" => "varchar(255)",
        "posted" => "DateTime"
    ];

    public function __construct(){
        $this->sql = Felta::getInstance()->sql;
        $this->create($this->structure);
    }
    public function put($title,$message,$url){
        $now = new \DateTime;
        $this->add([
            0,
            $title,
            $message,
            $url,
            $now->format("Y-m-d H:i:s")]
        );
        $this->sendNotification($title,$message,$url);
    }

    private function sendNotification($title,$message,$url){
        $email = new Email();
        $email->html(true);
        $email->setSMTP();
        $email->setTo(Felta::getConfig("email"));
        $email->setFrom(Felta::getConfig("smtp")["username"]);
        $email->setSubject("You've got a new notification from your website.");
        $email->setMessage(str_replace(["{title}","{message}","{url}"], [$title,$message,$url], $email->load("emails/message.html")));
        $email->send();
    }
    
    public function getAll(){
        return $this->select("*", []);
    }
    public function getByDate(){
        $dates = $this->getAll();
        usort($dates, function($a,$b){
            $t1 = strtotime($a['date']);
            $t2 = strtotime($b['date']);
            return $t1 - $t2;
        });
    }
    public function getWeekDay($date){
        return date(date('w',strtotime($date)));
    }
    public function getById($id){
        return $this->select("*",["id" => $id])[0];
    }
}
?>