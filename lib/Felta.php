<?php
namespace lib;

use lib\SimpleSQL;
use lib\Cleverload;
use lib\User\User;
use lib\Post\Statistics;
use lib\Post\Settings;

class Felta{
    
    public $sql;
    public $user;
    public $cleverload;

    public $update;
    public $statistics;
    public $settings;

    public $shop;
    public $blog;

    public static $instance;

    public function __construct(SimpleSQL $sql){
        $this->sql = $sql;
        $this->user = new User($sql);
        self::$instance = $this;
        $this->update = new Update("http://github.com/thomaskolmans/Kolmans");
        $this->statistics = new Statistics();
        $this->settings = new Settings();
        $this->createTables();
    }
    public static function getConfig($item, $key = false){
        $config = include(__DIR__."/../config.php");  
        foreach($config as $keys => $value){
            if($keys == $item){
                if($key){
                    return $keys;
                }
                return $value;
            }
        }
    }
    public static function getInstance(){
        if(isset(self::$instance)){
            return self::$instance;
        }
        return null;
    }
    public function setSQL($sql){
        $this->sql = $sql;
        return $this;
    }
    public function getSQL(){
        return $this->sql;
    }
    public function getHost(){
        $host = $_SERVER['SERVER_NAME'];
        $remove = explode(".", $host);
        unset($remove[0]);
        return implode(".", $remove);
    }
    public function setStatus($online){
        $this->sql->insert("felta",[
            0,
            $online,
            (new \DateTime())->format("Y-m-d H:i:s")
        ]);
    }
    public function getStatus(){
        return json_encode($this->sql->execute("SELECT * FROM `felta` ORDER BY `date` DESC LIMIT 1")[0]);
    }
    private function createTables(){
        if(!$this->sql->exists("felta",[])){
            $this->sql->create("felta",[
                "id" => "int auto_increment",
                "online" => "boolean",
                "date" => "DateTime"
            ],"id");
            $this->sql->insert("felta",[
                0,
                true,
                (new \DateTime())->format("Y-m-d H:i:s")
            ]);
        }
    }
}