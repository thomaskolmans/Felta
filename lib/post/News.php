<?php
namespace lib\post;

use lib\Felta;
use \DateTime;

class News extends Post {

    protected $name = "post_news";
    protected $structure = [
        "id" => "int auto_increment",
        "title" => "varchar(255)",
        "description" => "longtext",
        "location" => "varchar(255)",
        "image" => "varchar(255)",
        "date" => "DateTime",
        "posted" => "DateTime"
    ];

    public function __construct(){
        $this->sql = Felta::getInstance()->sql;
        $this->create($this->structure);
    }

    public function put($title,$description,$location,$image,$date){
        $now = new DateTime();
        $this->add([0,$title,$description,$location, $image, $date->format("Y-m-d H:i:s"),$now->format("Y-m-d H:i:s")]);
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

    public function getById($id){
        return $this->select("*",["id" => $id])[0];
    }
}
