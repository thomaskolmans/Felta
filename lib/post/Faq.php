<?php
namespace lib\post;

use lib\Felta;

class Faq {

    private $sql;

    private $id;
    private $name;
    private $questions;

    public function __construct($id, $name, $questions) {
        $this->sql = Felta::getInstance()->getSQL();

        $this->name = $name;
        $this->questions = $questions;
    }

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;

		return $this;
	}
}