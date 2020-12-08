<?php
namespace lib\post\blog;

use lib\Felta;
use \DateTime;

class Blog {
  
    private $sql;

    private $id;
    private $name;
    private $description;
    private $active;
    private $order; 
    private $createdAt; 
    private $updatedAt;
    
    public function __construct($id, $name, $description, $active, $order, $createdAt, $updatedAt){
        $this->sql = Felta::getInstance()->getSQL();

        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->active = $active;
        $this->order = $order;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function getAll(){
        $blogResults = Felta::getInstance()->getSQL()->select("*", "blog", []);
        if ($blogResults == null || count($blogResults) < 1) return [];

        $blogs = [];
        foreach($blogResults as $result){
            $blogs[] = Blog::fromResult($result);
        }

        return $blogs;
    }

    public static function getAllActive(){
        $blogResults = Felta::getInstance()->getSQL()->select("*", "blog", ["active" => true]);
        if ($blogResults != null && count($blogResults) < 1) return [];

        $blogs = [];
        foreach($blogResults as $result){
            $blogs[] = Blog::fromResult($result);
        }

        return $blogs;
    }

    public static function get($id){
        $blogResult = Felta::getInstance()->getSQL()->select("*", "blog", ["id" => $id])[0];
        if ($blogResult === null) return null;
        return Blog::fromResult($blogResult);
    }
    public static function fromResult($result) {
        return new Blog(
            $result["id"],
            $result["name"],
            $result["description"],
            $result["active"],
            $result["order"],
            new DateTime($result["createdAt"]),
            new DateTime($result["updatedAt"])
        );
    }

    public function save(){
        if ($this->sql->exists("blog", ["id" => $this->id])){
            $this->update();
        } else {
            $this->insert();
        }
    }
    
    public function insert(){
        $this->sql->insert("blog", [
            $this->id,
            $this->name,
            $this->description,
            $this->active,
            $this->order,
            $this->createdAt->format("Y-m-d H:i:s"),
            $this->updatedAt->format("Y-m-d H:i:s")
        ]);
    }
    
    public function update(){
        $this->sql->update("name", "blog", ["id" => $this->id], $this->name);
        $this->sql->update("description", "blog", ["id" => $this->id], $this->description);
        $this->sql->update("active", "blog", ["id" => $this->id], $this->active);
        $this->sql->update("order", "blog", ["id" => $this->id], $this->order);
        $this->sql->update("createdAt", "blog", ["id" => $this->id], $this->createdAt->format("Y-m-d H:i:s"));
        $this->sql->update("updatedAt", "blog", ["id" => $this->id], $this->updatedAt->format("Y-m-d H:i:s"));
    }

    public function delete(){
        $this->sql->delete("blog",["id" => $this->id]);
        $articles = $this->sql->select("*", "article", ["blog" => $this->id]);
        foreach($articles as $article) {
            Article::fromResult($article)->delete();
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

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
		return $this;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setDescription($description){
		$this->description = $description;
		return $this;
	}

	public function getActive(){
		return $this->active;
	}

	public function setActive($active){
		$this->active = $active;
		return $this;
	}

	public function getOrder(){
		return $this->order;
	}

	public function setOrder($order){
		$this->order = $order;
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
