<?php
namespace lib\post;

use lib\Felta;

class Agenda extends Post{

    protected $name = "post_agenda";
    protected $structure = [
        "id" => "int auto_increment",
        "title" => "varchar(255)",
        "description" => "longtext",
        "image" => "varchar(255)",
        "location" => "varchar(255)",
        "from" => "DateTime",
        "until" => "DateTime",
        "posted" => "DateTime"
    ];

    public function __construct(){
        $this->sql = Felta::getInstance()->sql;
        $this->create($this->structure);
    }
    public function put($title,$description,$image,$location,$from,$until){
        $now = new \DateTime;
        $this->add([
            0,
            $title,
            $description,
            $image,
            $location,
            $from->format("Y-m-d H:i:s"),
            $until->format("Y-m-d H:i:s"),
            $now->format("Y-m-d H:i:s")]
        );
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