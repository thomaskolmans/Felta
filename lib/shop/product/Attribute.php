<?php
namespace lib\shop\product;

use lib\helpers\UUID;
use lib\Felta;


class Attribute {
    
    private $sql;

    private $id;
    private $pid;
    private $name;
    private $value;

    public function __construct($id, $pid, $name, $value) {
        $this->sql = Felta::getInstance()->getSQL();

        $this->id = $id;
        $this->pid = $pid;
        $this->name = $name;
        $this->value = $value;
    }

    public static function fromResult($result) {
        return new Attribute(
            $result["id"],
            $result["pid"],
            $result["name"],
            $result["value"]
        );
    }

    public static function create($pid, $name, $value) {
        return new Attribute(
            UUID::generate(6),
            $pid,
            $name,
            $value
        );
    }
    
    public function save(){

    }

    public function insert(){ 
        $this->sql->insert("shop_product_variant_attribute", [
            $this->id,
            $this->pid,
            $this->name,
            $this->value
        ]);
    }
    
    public function update() {
        $this->sql->update("name","shop_product_variant_attribute",["id" => $this->id],$this->name);
        $this->sql->update("value","shop_product_variant_attribute",["id" => $this->id],$this->value);
    }
        
    public function expose(){
        $exposed = get_object_vars($this);
        unset($exposed["sql"]);
        return $exposed;
    }
    
    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
        return $this;
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
        return $this;
    }

    public function getValue(){
        return $this->value;
    }

    public function setValue($value){
        $this->value = $value;
        return $this;
    }
}