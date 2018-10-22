<?php
namespace lib\Post;

use lib\Felta;
class Statistics{

    public $sql;
    public $felta;

    public function __construct(){
        $this->felta = Felta::getInstance();
        $this->sql = $this->felta->sql;
        $this->createTables();
        $this->add();
    }

    public function add(){
        $now = new \DateTime();
        $ip = $_SERVER["REMOTE_ADDR"];
        if(!isset($_SESSION["visit_session"])){
            $this->sql->insert("visitors_total",[0,$ip,$now->format("Y-m-d H:i:s")]);
            $_SESSION["visit_session"] = true;
        }
        if(!$this->sql->exists("visitors_unique",["ip" => $ip])){
            $this->sql->insert("visitors_unique",[0,$ip,$now->format("Y-m-d H:i:s")]);
        }
        $details = json_decode(file_get_contents("https://extreme-ip-lookup.com/json/"));
        if($details->lon != 0){
            $this->sql->insert("visitors_total_location",[
                0,
                $ip,
                $details->lat,
                $details->lon,
                $now->format("Y-m-d H:i:s")
            ]);
            if(!$this->sql->exists("visitors_unique_location",["ip" => $ip])){
                $this->sql->insert("visitors_unique_location",[
                    0,
                    $ip,
                    $details->lat,
                    $details->lon,
                    $now->format("Y-m-d H:i:s")
                ]);
            }
        }
    }
    
    public function getUniqueVisitors(){
        $this->sql->count("visitors_unique");
    }

    public function getTotalVisitors(){
        return $this->sql->count("visit_total");
    }

    public function createTables(){
        if(!$this->sql->exists("visitors_total",[])){
            $this->sql->create("visitors_total",[
                "id" => "int auto_increment",
                "ip" => "varchar(65)",
                "date" => "DateTime"
                ],"id");
            $this->sql->create("visitors_unique",[
                "id" => "int auto_increment",
                "ip" => "varchar(65)",
                "date" => "DateTime"
                ],"id");
            $this->sql->create("visitors_total_location",[
                "id" => "int auto_increment",
                "ip" => "varchar(65)",
                "lat" => "varchar(255)",
                "long" => "varchar(255)",
                "date" => "DateTime"
            ],"id");
            $this->sql->create("visitors_unique_location",[
                "id" => "int auto_increment",
                "ip" => "varchar(65)",
                "lat" => "varchar(255)",
                "long" => "varchar(255)",
                "date" => "DateTime"
            ],"id");
        }
    }
}
?>