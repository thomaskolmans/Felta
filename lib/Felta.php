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

    public static $instance;

    public function __construct(SimpelSQL $sql){
        $this->sql = $sql;
        $this->user = new User($sql);
        self::$instance = $this;
        $this->update = new Update("http://github.com/thomaskolmans/Kolmans");
        $this->statistics = new Statistics();
        $this->settings = new Settings();
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
    public function getInstance(){
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

}