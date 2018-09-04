<?php

namespace lib\Post\Blog;

class Blog{
  
    private $sql;

    public function __construct(){
        $this->sql = Felta::getInstance()->getSQL();
        $this->createTables();
    }

    public static function create(){

    }

    private function createTables(){
        if(!$this->sql->exists("blog",[])){
            $this->sql->create("blog",[
            ],"id");
        }
    }  
}

?>