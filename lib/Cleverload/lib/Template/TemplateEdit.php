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

    private $editNodes;
    private $langNodes;


    public function __construct($dom){
        $this->dom = $dom;
        $this->editNodes = $this->dom->getElementsByTagName('edit');
        $this->langNodes = $this->dom->getElementsByTagName('lang');
        $this->sql = Felta::getInstance()->sql;
        $this->language = new Language($this->sql);
        $this->edit = new Edit();
        $this->compileEditNodes();
        $this->compileLangNodes();
        $this->insertEditText();
    }

    public function compileEditNodes(){
        foreach($this->editNodes as $node){
            $value = $this->getHTML($node);
            $val = $node->nodeValue;
            if($node->hasAttribute("fid")){
                $id = $node->getAttribute("fid");
            }
            if($node->hasChildNodes()){
                $childs = $node->childNodes;
                foreach($childs as $child){
                    $class = get_class($child);
                    $reflection = new \ReflectionClass($class);
                    if($reflection->getShortName() == "DOMElement"){
                        if($child->hasAttribute("edit")){
                            $id = $child->getAttribute("edit");
                        }
                    }
                }
            }
            if(count($node->childNodes) > 1){
                $this->saveText($id,$value,$this->language->getDefault());
            }else{
                if($node->hasChildNodes()){
                    $n = $node->childNodes[0];
                    if(isset($n->tagName)){
                        switch (strtoupper($n->tagName)) {
                            case 'H1':
                            case 'H2':
                            case 'H3':
                            case 'H4':
                            case 'H5':
                                $this->saveText($id,$val,$this->language->getDefault());
                                break;
                            case 'IMG':
                            case 'IFRAME':
                                if($child->hasAttribute("src")){
                                    $this->saveText($id,$child->getAttribute("src"),$this->language->getDefault());
                                }
                                break;
                        }
                    }
                }

            }
            
        }
        return $this->dom;
    }
    public function compileLangNodes(){
        foreach($this->langNodes as $node){
            if($node->hasChildNodes()){
                $langs = [];
                foreach($node->childNodes as $langNode){
                    $class = get_class($langNode);
                    $reflection = new \ReflectionClass($class);
                    if($reflection->getShortName() === "DOMElement"){
                        if($langNode->hasAttribute("lang")){
                            $language = $langNode->getAttribute("lang");
                            $langs[$language] = $this->getHTML($langNode);
                        }
                    }
                }
                $languagelist = $this->language->getLanguageList();
                $languages = array_keys($langs);
                if(is_array($languages)){
                    $outcome = [];
                    foreach($languages as $l){
                        if(in_array($this->language->languages[$l], $languagelist)){
                            $outcome[] = $l;
                        }
                    }
                    $languages = $outcome;
                }
                $l = $this->language->get((array) $languages);
                $node->nodeValue = $langs[$l];
            }
        }
        return $this->dom; 
    }

    public function insertEditText(){
        foreach($this->editNodes as $node){
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
                            case "iframe":
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
    private function saveText($id,$text,$language){
        if(!$this->sql->exists("post_edit",["id" => $id, "language" => $language])){
            $this->sql->insert("post_edit",array(
                0,
                $id,
                $text,
                $language
            ));
        }
    }
    private function getHTML($node){
        if($node instanceof DOMNode){
            $html = "";
            $children = $node->childNodes;
            foreach($children as $child){
                $html .= $node->ownerDocument->saveHTML($child);
            }
            return $html;
        }else{
            return $node->ownerDocument->saveHTML($node);
        }
    }
}

?>