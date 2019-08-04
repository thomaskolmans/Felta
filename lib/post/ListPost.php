<?php
namespace lib\post;

abstract class ListPost {
    
    public $name;
    
    public function add(){
        $this->insert("list_".$this->name,[0]);
    }
    public function createTables(){
        if(!$this->exists("list_".$this->name,[])){
            $this->create("list_".$this->name,[
                "id" => "int auto_increment",
                "src" => "varchar(255)",
                "src1" => "varchar(255)",
                "src3" => "varchar(255)"
                ],"id");
        }
    }
}
?>