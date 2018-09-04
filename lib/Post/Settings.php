<?php
namespace lib\Post;

class Settings{
    
    public $sql;

    public function __construct(){
        $this->sql = \lib\Felta::getInstance()->sql;
        $this->createTables();
        $this->getDefaults();
    }
    public function get($name){
        return $this->sql->select("value","settings",["name" => $name]);
    }
    public function set($name,$value){
        if($this->sql->exists("settings",["name" => $name])){
            $this->change($name,$value);
            return $this;
        }
        $this->add($name,$value);
        return $this;
    }
    public function add($name,$value){
        if(!$this->sql->exists("settings",["name" => $name])){
            $this->sql->insert("settings",[0,$name,$value]); 
        }
        return $this;
    }
    public function change($name,$to){
        $this->sql->update("value","settings",["name" => $name],$to);
        return $this;
    }
    public function getDefaults(){
        $this->add("website_url",\lib\Felta::getConfig("website_url"));
        $this->add("website_name",\lib\Felta::getConfig("website_name"));
        $this->add("default_dir",\lib\Felta::getConfig("default_dir"));
    }
    public function createTables(){
        if(!$this->sql->exists("settings",[])){
            $this->sql->create("settings",[
                'id' => 'int auto_increment',
                'name' => 'varchar(255)',
                'value' => 'varchar(255)'
                ],'id');
        }
    }
}