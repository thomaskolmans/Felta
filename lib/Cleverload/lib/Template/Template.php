<?php
namespace lib\Template;

use lib\Routing\Router;
use lib\Routing\Route;
use lib\Cleverload;

class Template extends Router{
    
    public $route = null;
    public $filepath = "";
    public $dom;

    public static $php = [];

    public function __construct($input){
        libxml_use_internal_errors(true);
        if($input instanceof Route){
            $this->route = $input;
            $this->filepath = $input->getFile();
            $this->dom = $this->getDomFromFile($this->getFile());
            $this->load();
        }else{
            $this->dom = $this->getDom($input);
            $this->load();
        }

    }
    public function getTemplateTags(){
        return Cleverload::getConfig("template_tags");
    }
    public function getFile(){
        if(file_exists($this->filepath)){
            return $this->filepath;
        }
        return $this->redirect()->error("404");
    }
    public function getFileInfo(){
        return pathinfo($this->getFile());
    }
    public function getDomFromFile($file){
        $dom = new \DOMDocument();
        $content = file_get_contents($file);
        $content = $this->extractPHP($content);
        $dom->loadHTML($content);
        return $dom;
    }
    public function getDom($content){
        $this->dom = new \DOMDocument();
        $this->dom->loadHTML($this->extractPHP($content));
        return $this->dom;
    }
    public function getDomSinExtract($content){
        $this->dom = new \DOMDocument();
        $this->dom->loadHTML($content);
        return $this->dom;
    }
    public function getContent(){
        return $this->dom->saveHTML();
    }
    public function saveContent($content){
        $this->getDomSinExtract($content);
        return $this;
    }
    public function getAllowdExtensionsForTags(){
        return Cleverload::getConfig("extensions_template_tags");
    }
    public function getAllowdExtensionsForPlugins(){
        return Cleverload::getConfig("extension_template_plugin");
    }
    public function extractPHP($content){
        $matches = self::getInBetween($content,"<?php","?>");
        foreach($matches as $match){
            $uid = uniqid();
            $content = str_replace($match," ".$uid." ",$content);
            self::$php[] = array($uid,$match);
        }
        return $content;
    }
    public static function insertPHP($content){
        $matches = self::getInBetween($content,"<?php"," ?>");
        for($i = 0; $i < count($matches); $i++){
            $match = trim($matches[$i]);
            if($i <= count(self::$php) - 1){
                if(self::$php[$i][0] == $match){
                    $content = str_replace(trim($match), self::$php[$i][1], $content);
                }
                continue;
            }
        }
        return $content;
    }
    public static function getInBetween($string, $start, $end){
        $contents = array();
        $startLength = strlen($start);
        $endLength = strlen($end);
        $startFrom = $contentStart = $contentEnd = 0;
        while (false !== ($contentStart = strpos($string, $start, $startFrom))) {
            $contentStart += $startLength;
            $contentEnd = strpos($string, $end, $contentStart);
            if (false === $contentEnd) {
                break;
            }
            $contents[] = substr($string, $contentStart, $contentEnd - $contentStart);
            $startFrom = $contentEnd + $endLength;
        }

        return $contents;
    }

    public function load(){
        new TemplateLoader($this);
    }
}