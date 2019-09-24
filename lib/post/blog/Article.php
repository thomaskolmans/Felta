<?php
namespace lib\post\blog;

use lib\Felta;
use lib\helpers\UUID;
use \DateTime;

class Article {

    private $sql; 

    private $id;
    private $title;
    private $description;
    private $images;
    private $body;
    private $active;
    private $activeFrom;
    private $createdAt; 
    private $updatedAt;

    public function __construct(
        $id = "",
        $title = "", 
        $description = "", 
        $images = [], 
        $body = "", 
        $active = false, 
        $activeFrom,
        $createdAt,
        $updatedAt
    ){
        $this->sql = Felta::getInstance()->getSQL();

        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->images = $images;
        $this->body = $body;
        $this->active = $active;
        $this->activeFrom = $activeFrom;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function get($id){
        $articleResult = Felta::getInstance()->getSQL()->select("*", "article", ["id" => $this->id]);
        if ($articleResult == null) return null;
        return Article::fromResult($articleResult);
    }
    
    public static function fromResult($result){
        $images = [];
        $article = new Article(
            $result["id"],
            $result["title"],
            $result["description"],
            [],
            $result["body"],
            $result["active"],
            new DateTime($result["activeFrom"]),
            new DateTime($result["createdAt"]),
            new DateTime($result["updatedAt"])
        );
        return $article;
    }

    public function save(){
        if ($this->sql->exists("article", ["id" => $this->id])){
            $this->update();
        } else {
            $this->insert();
        }
    }
    
    public function insert(){
        $this->sql->insert("article", [
            $this->id,
            $this->title,
            $this->description,
            $this->body,
            $this->active,
            $this->activeFrom->format("Y-m-d H:i:s"),
            $this->createdAt->format("Y-m-d H:i:s"),
            $this->updatedAt->format("Y-m-d H:i:s")
        ]);
    }
    
    public function update(){
        $this->sql->update("name", "article", ["id" => $this->$id], $this->name);
        $this->sql->update("description", "article", ["id" => $this->$id], $this->description);
        $this->sql->update("body", "article", ["id" => $this->$id], $this->body);
        $this->sql->update("active", "article", ["id" => $this->$id], $this->active);
        $this->sql->update("activeFrom", "article", ["id" => $this->$id], $this->createdAactiveFromt->format("Y-m-d H:i:s"));
        $this->sql->update("createdAt", "article", ["id" => $this->$id], $this->createdAt->format("Y-m-d H:i:s"));
        $this->sql->update("updatedAt", "article", ["id" => $this->$id], $this->updatedAt->format("Y-m-d H:i:s"));
    }

    public function delete(){
        $this->sql->delete("article",["id" => $this->id]);
        $comments = $this->sql->select("*", "comment", ["article_id" => $this->id]);
        foreach($comments as $comment) {
            Comment::fromResult($comment)->delete();
        }
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

	public function getTitle(){
		return $this->title;
	}

	public function setTitle($title){
		$this->title = $title;
		return $this;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setDescription($description){
		$this->description = $description;
		return $this;
	}

	public function getImages(){
		return $this->images;
	}

	public function setImages($images){
		$this->images = $images;
		return $this;
	}

	public function getBody(){
		return $this->body;
	}

	public function setBody($body){
		$this->body = $body;
		return $this;
	}

	public function getActive(){
		return $this->active;
	}

	public function setActive($active){
		$this->active = $active;
		return $this;
	}

    public function getActiveFrom(){
		return $this->activeFrom;
	}

	public function setActiveFrom($activeFrom){
		$this->activeFrom = $activeFrom;
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

?>