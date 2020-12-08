<?php
namespace lib\post;

use \DateTime;
use lib\Felta;

class PageMetaTags {

    private $sql;

    private $id;
    private $title;
    private $description;
	private $keywords;
	private $image; 

    private $createdAt;
    private $updatedAt;

    public function __construct($id, $name, $questions, $createdAt, $updatedAt) {
        $this->sql = Felta::getInstance()->getSQL();

        $this->id = $id;
        $this->name = $name;
        $this->questions = $questions;

        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
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
	
	public function getCreatedAt(){
		return $this->createdAt;
	}
	
	public function setCreatedAt($createdAt){
		$this->createdAt = $createdAt;
		return $this;
	}
	
	public function getUpdatedAt(){
		return $this->updatedAt;
	}
	
	public function setUpdatedAt($updatedAt){
		$this->updatedAt = $updatedAt;
		return $this;
	}
	
}
