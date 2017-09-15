<?php
namespace lib\Post;

use lib\Felta;
use lib\Helpers\Language;

class Edit extends Post{
    
    public $language;

    protected $name = "post_edit";

    public function __construct(){
        $this->sql = Felta::getInstance()->sql;
        $this->language = new Language($this->sql);
        $this->create([
            "n" => "int auto_increment",
            "id" => "varchar(255)",
            "text" => "longtext",
            "language" => "varchar(50)"
            ]);
    }
    public function setText($id,$lang,$text){
        if($this->sql->exists($this->name,["id" => $id, "language" => $lang])){
            return $this->update("text",["id" => $id,"language" => $lang],$text);
        }else{
            return $this->add([0,$id,$text,$lang]);
        }
    }
    public function getAvailableLanguages($id){
        return $this->select("language",["id" => $id]);
    }
    public function getText($id){
        $languages = $this->getAvailableLanguages($id);
        $languagelist = $this->language->getLanguageList();
        if(is_array($languages)){
            $outcome = [];
            foreach($languages as $result){
                if(in_array($this->language->languages[$result["language"]], $languagelist)){
                    $outcome[] = $result["language"];
                }
            }
            $languages = $outcome;
        }
        $language  = $this->language->get((array) $languages);
        return $this->select("text",["id" => $id, "language" => $language]);
    }

    public function get($id,$lang){
        if($this->sql->exists($this->name,["id" => $id, "language" => $lang])){
            return $this->select("text",["id" => $id, "language" => $lang]);
        }
        return $this->select("text",["id" => $id,"language" => $this->language->getDefault()]);
    }
}
?>