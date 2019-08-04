<?php
namespace lib\post;

abstract class Post implements IPost{

    protected $name;
    protected $structure;
    protected $sql;

    private $sql_structure;

    public function create($structure){
        $this->structure = $structure;
        if(!$this->sql->exists($this->name,null)){
            $this->sql->create($this->name,$structure,$this->getPrimary($structure));
        }
        return $this;
    }
    public function getPrimary($structure){
        return array_keys($structure)[0];
    }
    public function add(array $values){
        $this->sql->insert($this->name,$values);
        return $this;
    }
    public function delete(array $whereequals){
        $this->sql->delete($this->name,$whereequals);
        return $this;
    }
    public function sortByDate($dates,$limit = null){
        usort($dates, function($a,$b){
            $t1 = strtotime($a['date']);
            $t2 = strtotime($b['date']);
            return $t1 - $t2;
        });
    }
    public function update($column,array $whereequals,$to){
        $this->sql->update($column,$this->name,$whereequals,$to);
        return $this;
    }
    public function select($column,$whereequals, $limit = null){
        return $this->sql->select($column,$this->name,$whereequals,$limit);
    }
    public function setStructure(array $structure){
        $this->structure = $structure;
        return $this;
    }
    public function getStructure(){
        return $this->structure;
    }
    public function setName($name){
        $this->name = "post_".$name;
        return $this;
    }
    public function getName(){
        return $this->name;
    }
}