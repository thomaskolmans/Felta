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
                "id" => "int auto_increment",
                "title" => "varchar(556)",
                "initiated" => "DateTime"
            ],"id");
            $this->sql->create("blog_article", [
                "id" => "int auto_increment",
                "blog_id" => "int",
                "title" => "varchar(556)",
                "author" => "varchar(255)",
                "content" => "longtext",
                "posted" => "DateTime"
            ], "id");
            $this->sql->create("blog_comment", [
                "id" => "int auto_increment",
                "article_id" => "int",
                "parent_id" => "int",
                "name" => "varchar(255)",
                "comment" => "longtext",
                "posted" => "DateTime"
            ], "id");
        }
    }  
}

?>