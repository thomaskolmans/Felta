<?php
namespace lib\post\blog;

class Blog{
  
    private $sql;

    public function __construct(){
        $this->sql = Felta::getInstance()->getSQL();
    }

    public static function create(){

    }

}

?>