<?php
namespace lib\post;

use lib\Felta;

class FaqQuestion {

    private $sql;

    private $id;
    private $question;
    private $answer;

    private $createdAt;
    private $updatedAt;

    public function __construct($id, $question, $answer, $createdAt, $updatedAt) {
        $this->sql = Felta::getInstance()->getSQL();

        $this->id = $id;
        $this->question = $question;
        $this->answer = $answer;

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
	
	public function getQuestion(){
		return $this->question;
	}
	
	public function setQuestion($question){
		$this->question = $question;
		return $this;
	}
	
	public function getAnswer(){
		return $this->answer;
	}
	
	public function setAnswer($answer){
		$this->answer = $answer;
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
