<?php
namespace lib;

use lib\SimpleSQL;
use lib\Cleverload;
use lib\user\User;
use lib\post\Statistics;
use lib\post\Settings;

class Felta {
    
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
        self::$instance = $this;
        $this->user = new User($sql);
        $this->statistics = new Statistics();
        $this->settings = new Settings();
        self::$instance = $this;
    }

    public static function getConfig($item, $key = false){
        $sqlResult = Felta::getInstance()->getSQL()->select("value", "settings", ["name" => $item]);
        if ($sqlResult) {
            return $sqlResult;
        }
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

}