<?php
namespace lib\Template;

use lib\post\Edit;
use lib\helpers\Language;
use lib\database\SQL;
use lib\Felta;

use \DateTime;

class TemplateEdit{
    
    public $sql;
    public $edit;
    public $dom;

    public $language;

    private $editNodes;
    private $langNodes;
    private $headNodes;


    public function __construct($dom){
        $this->dom = $dom;
        $this->editNodes = $this->dom->getElementsByTagName('edit');
        $this->langNodes = $this->dom->getElementsByTagName('lang');
        $this->headNodes = $this->dom->getElementsByTagName('head');

        $this->sql = Felta::getInstance()->sql;
        $this->language = new Language($this->sql);
        $this->edit = new Edit();

        $this->compileEditNodes();
        $this->compileLangNodes();
        $this->compileHeadNodes();
        $this->insertEditText();
    }

    public function compileEditNodes(){
        foreach($this->editNodes as $node){

            $value = "";
            $val = $node->nodeValue;

            if($node->hasAttribute("fid")){
                $id = $node->getAttribute("fid");
            }

            if($node->hasChildNodes()){
                $children = $node->childNodes;
                foreach($children as $child){
                    $class = get_class($child);
                    $reflection = new \ReflectionClass($class);
                    if($reflection->getShortName() == "DOMElement"){
                        if($child->hasAttribute("edit")){
                            $id = $child->getAttribute("edit");
                            if($child->hasChildNodes()){
                                $childNodes = $child->childNodes;
                                foreach($childNodes as $childNode){
                                    $value .= $this->getHTML($childNode);
                                }
                            }
                        }
                    }
                }
            }

            if(count($node->childNodes) > 1){
                $this->saveText($id, $value, $this->language->getDefault());
            } else {
                if($node->hasChildNodes()){
                    $childNode = $node->childNodes[0];
                    $found = false;
                    if(isset($childNode->tagName)){
                        switch (strtoupper($childNode->tagName)) {
                            case 'H1':
                            case 'H2':
                            case 'H3':
                            case 'H4':
                            case 'H5':
                                $this->saveText($id, $val,$this->language->getDefault());
                                $found = true;
                            break;
                            case 'IMG':
                            case 'IFRAME':
                                if($child->hasAttribute("src")){
                                    $this->saveText($id, $child->getAttribute("src"), $this->language->getDefault());
                                    $found = true;
                                }
                            break;
                            default:
                                $this->saveText($id, $value, $this->language->getDefault());
                                $found = true;
                            break;
                        }
                    }
                    if(!$found){ 
                        $this->saveText($id, $value, $this->language->getDefault());
                    }
                }
            }
        }
        return $this->dom;
    }

    public function compileLangNodes(){
        foreach($this->langNodes as $node){
            if($node->hasChildNodes()){
                $availableLanguages = $this->language->getLanguageList();
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
                $languages = array_keys($langs);
                if(is_array($languages)){
                    $outcome = [];
                    foreach($languages as $language){
                        if(in_array($language, $availableLanguages)){
                            $outcome[] = $language;
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

    public function compileHeadNodes(){
        if($this->headNodes->length > 1){
            $availableLanguages = $this->language->getLanguageList();
            $headLanguages = [];
            $availableHeadLanguages = [];
            
            for($i = 0; $i < $this->headNodes->length; $i++){
                $node = $this->headNodes[$i];
                if($node->hasAttribute("lang")){
                    $headLanguages[] = $node->getAttribute("lang");
                }
            }
            
            foreach($headLanguages as $headLanguage) {
                if (in_array($headLanguage, $availableLanguages)) {
                    $availableHeadLanguages[] = $headLanguage;
                }
            }

            $selectedLanguage = $this->language->get((array) $availableHeadLanguages);
            for($i = 0; $i < $this->headNodes->length; $i++){
                $node = $this->headNodes[$i];
                if($node->hasAttribute("lang") && $node->getAttribute("lang") !== $selectedLanguage){
                    $node->parentNode->removeChild($node);
                }
            }
        }
    }

    public function insertEditText(){
        foreach($this->editNodes as $node){
            if($node->hasChildNodes()){
                $children = $node->childNodes;
                foreach($children as $child){
                    $class = get_class($child);
                    $reflection = new \ReflectionClass($class);
                    if($reflection->getShortName() == "DOMElement"){
                        if($child->hasAttribute("edit")){
                            $id = $child->getAttribute("edit");
                        }
                        switch(strtolower($child->tagName)){
                            case "img":
                            case "iframe":
                                $child->setAttribute("src", $this->edit->getText($id));
                            break;
                            default:
                                $child->nodeValue = $this->edit->getText($id);
                            break;
                        }
                    }
                }
            }
        }
        $this->dom->getElementsByTagName("html")[0]->setAttribute("lang",$this->language->get($this->language->getLanguageList()));
    }

    private function saveText($id,$text,$language){
        $now = new DateTime();
        if(!$this->sql->exists("post_edit",["key" => $id, "language" => $language])){
            $this->sql->insert("post_edit",array(
                0,
                $id,
                $text,
                $language,
                $now->format("Y-m-d H:i:s"),
                $now->format("Y-m-d H:i:s")
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
        }

        return $node->ownerDocument->saveHTML($node);
    }
}

?>