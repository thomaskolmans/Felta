<?php
namespace lib\Template;

use lib\Post\Edit;
use lib\Helpers\Language;
use lib\Database\SQL;
use \DateTime;
use lib\Felta;

class TemplateEdit{
    
    public $sql;
    public $edit;
    public $dom;

    public $language;
    public $text = [];

    private $nodes;


    public function __construct($dom){
        $this->dom = $dom;
        $this->nodes = $this->dom->getElementsByTagName('edit');
        $this->sql = Felta::getInstance()->sql;
        $this->language = new Language($this->sql);
        $this->edit = new Edit();
        $this->compile();
    }
    public function compile(){
        foreach($this->nodes as $node){
            $value = $node->nodeValue;
            if($node->hasChildNodes()){
                $childs = $node->childNodes;
                foreach($childs as $child){
                    if(isset($child->tagname)){
                        if($child->tagname() == "img" || $child->tagname() == "iframe"){
                            if($child->hasAttribute("src")){
                                $value = $child->getAttribute("src");
                            }
                        }
                    }
                    $class = get_class($child);
                    $reflection = new \ReflectionClass($class);
                    if($reflection->getShortName() == "DOMElement"){
                        if($child->hasAttribute("edit")){
                            $id = $child->getAttribute("edit");
                        }
                    }
                }
            }
            $this->text[$id] = $value;
        }
        $this->updateDatabase();
        $this->putText();
        return $this->dom;
    }
    public function updateDatabase(){
        $date = new \DateTime("now");
        $date->setTimestamp(time());
        $language = $this->language->getDefault();
        foreach(array_keys($this->text) as $id){
            if(!$this->sql->exists("post_edit",["id" => $id])){
                $this->sql->insert("post_edit",array(
                    0,
                    $id,
                    $this->text[$id],
                    $language
                ));
            }
        }
    }
    public function putText(){
        foreach($this->nodes as $node){
            if($node->hasChildNodes()){
                $childs = $node->childNodes;
                foreach($childs as $child){
                    $class = get_class($child);
                    $reflection = new \ReflectionClass($class);
                    if($reflection->getShortName() == "DOMElement"){
                        if($child->hasAttribute("edit")){
                            $id = $child->getAttribute("edit");
                        }
                        switch($child->tagName){
                            case "img":
                                $child->setAttribute("src",$this->edit->getText($id));
                            break;
                            default:
                                $child->nodeValue = $this->edit->getText($id);
                            break;
                        }
                    }
                }
            }
        }
    }
}

?>