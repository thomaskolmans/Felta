<?php
namespace lib\post;

use lib\Felta;
use lib\helpers\Language;
use \DateTime;

class Edit extends Post{
    
    public $language;

    protected $name = "post_edit";

    public function __construct(){
        $this->sql = Felta::getInstance()->sql;
        $this->language = new Language($this->sql);
        $this->create([
            "id" => "int auto_increment",
            "key" => "varchar(255)",
            "text" => "longtext",
            "language" => "varchar(50)",
            "createdAt" => "DateTime",
            "updatedAt" => "DateTime"
        ]);
    }

    public function setText($id, $lang, $text){
        $now = new DateTime();
        if($this->sql->exists($this->name,["key" => $id, "language" => $lang])){
            $this->update("updatedAt",["key" => $id, "language" => $lang], $now->format("Y-m-d H:i:s"));
            return $this->update("text",["key" => $id, "language" => $lang],$text);
        } else {
            return $this->add([0, $id, $text, $lang, $now->format("Y-m-d H:i:s"), $now->format("Y-m-d H:i:s")]);
        }
    }

    public function getAvailableLanguages($id){
        return $this->select("language",["key" => $id]);
    }

    public function getText($id){
        $languages = $this->getAvailableLanguages($id);
        $languageList = $this->language->getLanguageList();
        if(is_array($languages)){
            $outcome = [];
            foreach($languages as $l){
                if(in_array($this->language->languages[$l["language"]], $languageList)){
                    $outcome[] = $l["language"];
                }
            }
            $languages = $outcome;
        }
        $language  = $this->language->get((array) $languages);
        return $this->select("text",["key" => $id, "language" => $language]);
    }

    public function get($id,$lang){
        if($this->sql->exists($this->name,["key" => $id, "language" => $lang])){
            return $this->select("text", ["key" => $id, "language" => $lang]);
        }
        return $this->select("text", ["key" => $id,"language" => $this->language->getDefault()]);
    }
}
