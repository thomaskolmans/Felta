<?php
namespace lib\post\blog;

use lib\Felta;
use \DateTime;

class ArticleImage {

    private $sql; 

    private $id;
    private $article;
    private $url;
    private $description;
    private $order;
    private $createdAt;

    public function __construct(
        $id = "",
        $article = "",
        $url = "",
        $description = "",
        $order = 0, 
        $createdAt
    ){
        $this->sql = Felta::getInstance()->getSQL();
        
        $this->id = $id;
        $this->article = $article;
        $this->url = $url;
        $this->description = $description;
        $this->createdAt = $createdAt;
    }

    public static function get($id){
        $articleImageResult = Felta::getInstance()->getSQL()->select("*", "article_image", ["id" => $id])[0];
        if ($articleImageResult == null) return null;
        return ArticleImage::fromResult($articleImageResult);
    }

    public static function getFromArticle($id) {
        $articleImageResults = Felta::getInstance()->getSQL()->select("*", "article_image", ["article" => $id]);
        $images = [];

        if ($articleImageResults == null || count($articleImageResults) < 1) return $images;

        foreach($articleImageResults as $result) {
            $images[] = ArticleImage::fromResult($result);
        }
        return $images;
    }
    
    public static function fromResult($result){
        $articleImage = new ArticleImage(
            $result["id"],
            $result["article"],
            $result["url"],
            $result["description"],
            new DateTime($result["createdAt"])
        );
        return $articleImage;
    }

    public function save(){
        if ($this->sql->exists("article_image", ["id" => $this->id])){
            $this->update();
        } else {
            $this->insert();
        }
    }
    
    public function insert(){
        $this->sql->insert("article_image", [
            $this->id,
            $this->article,
            $this->url,
            $this->description,
            $this->order,
            $this->createdAt->format("Y-m-d H:i:s")
        ]);
    }
    
    public function update(){
        $this->sql->update("url", "article_image", ["id" => $this->id], $this->url);
        $this->sql->update("description", "article_image", ["id" => $this->id], $this->description);
        $this->sql->update("order", "article_image", ["id" => $this->id], $this->order);
        $this->sql->update("createdAt", "article_image", ["id" => $this->id], $this->createdAt->format("Y-m-d H:i:s"));
    }

    public function delete(){
        $this->sql->delete("article_image", ["id" => $this->id]);
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

	public function getArticle(){
		return $this->article;
	}
	
	public function setArticle($article){
		$this->article = $article;
		return $this;
	}
	
	public function getUrl(){
		return $this->url;
	}
	
	public function setUrl($url){
		$this->url = $url;
		return $this;
	}
	
	public function getDescription(){
		return $this->description;
	}
	
	public function setDescription($description){
		$this->description = $description;
		return $this;
	}
	
	public function getCreatedAt(){
		return $this->createdAt;
	}
	
	public function setCreatedAt($createdAt){
		$this->createdAt = $createdAt;
		return $this;
	}
	
}