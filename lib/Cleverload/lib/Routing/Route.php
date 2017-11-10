<?php
namespace lib\Routing;

use lib\Template\Template;
use lib\Cleverload;
use Exception;

class Route{

    private $router;

    public $uri;
    public $domain;

    public $groupstack;

    private $file = null;
    private $action = null;

    protected $methods = [];
    protected $parameters = [];

    protected $wheres = [];
    protected $if = true;

    protected $sections;
    protected $sectioncount;

    public function __construct($methods,$uri,$action){
        if(Cleverload::getInstance()->getCaseSensitive()){
           $this->uri = $uri;
       }else{
            $this->uri = strtolower($uri);
       }

        $this->setAction($action);
        $this->methods = $methods;
        $this->run();
    }
    public function run(){
        $this->sections = $this->setSections();
        $this->sectioncount = $this->setSectionCount();

        $this->setParameters();
    }
    public function getResponse(){
        $this->run();
        if(is_file(Cleverload::getInstance()->getStaticFilesDir().$this->uri)){
            $extension = pathinfo($this->uri)["extension"];
            $ctype = "text/html";
            switch($extension){
                case "zip": $ctype = "application/zip"; break;
                case "jpeg":
                case "jpg": $ctype = "image/jpg"; break;
                case "png": $ctype = "image/png"; break;
                case "css": $ctype = "text/css"; break;
                case "js": $ctype = "text/javascript"; break;
            }
            header("Content-type: ".$ctype);
            echo(file_get_contents(Cleverload::getInstance()->getStaticFilesDir().$this->uri));
            exit;
        }
        $matchedroute = $this->getMatch($this->getRouter());
        $matchedroute->load();
        $this->getRouter()->routes->clear();
        $this->getRouter()->response->send();
    }
    public function where($variable,$regex){
        $this->wheres[$variable] = $regex;
        return $this;
    }
    public function when($true){
        $this->if = $true;
        return $this;
    }
    public function primary(){
        if(count($this->getParameters()) < 1){
            $this->getRouter()->addDefault($this);
        }else{
            throw new \Exception("You can't set the default to a path with a variable");
        }
        return $this;
    }
    public function getClosest($routes){
        if(count($routes) < 1){
            return $this->getRouter()->response->notFound();
        }
        if($this->sectioncount > 0){
            for($i = 0; $i < $this->sectioncount; $i++){
                $routes = $this->matchSectionToRoutes($i,$routes);
                if(count($routes) == 1){
                    return $routes[0];
                }else if(count($routes) < 1){
                    return $this->getRouter()->response->notFound();
                }
            }
            return $routes[0];
        }else{
            $results = [];
            foreach($routes as $route){
                if($this->sectioncount === $route->sectioncount || $this->sectioncount === ($route->sectioncount -1)){
                    $results[] = $route;
                }
            }   
            if(count($results) > 0){
                return $results[0];
            }else{
                $this->getRouter()->response->notFound();
            }
        }

    }
    public function getMatch(Router $router){
        $routes = $router->getRoutes();
        $found = false;
        for($i = 0; $i < $this->sectioncount; $i++){
            if(!$found && count($routes) > 0){
                $routes = $this->matchSectionToRoutes($i,$routes);
                if(count($routes) == 1){
                    $found = true;
                    break;
                }
                continue;
            }
        }
        if(!$found){
            if($this->sectioncount <= 1){
                return $this->getRouter()->getDefault();
            }
            if(count($routes) > 1){
                return $this->getClosest($routes);
            }
            return $this->getRouter()->response->notFound();
        }
        return $routes[0];
    }
    private function matchSectionToRoutes($i,$routes){
        $matches = [];
        foreach($routes as $route){
            if($this->equalsSection($i,$route)){
                if(count($this->getWhere()) > 0 && $this->getSection($i)->isValue()){
                    if(!preg_match("/^".$route->getWhere()[$this->getSection($i)->clean()]."+$/",$this->getSection($i)->get())){
                        continue;
                    }
                }
                if(!$route->getIf()) continue;
                if($route->getDomain() != null){
                    if($route->getDomain() === $this->getDomain()) continue;
                }
                $matches[] = $route;
            }
        }
        return $matches;
    }
    private function equalsSection($i,$route){

        if($route->getSectionCount() >= $this->getSectionCount() && $route->hasMethod($this->getMethods()[0])){
            if($this->getSection($i)->toString() === $route->getSection($i)->toString() || $this->getSection($i)->isValue()){
                return true;
            }
        }
        return false;
    }
    public function setGroupstack($groupstack){
        $this->groupstack = $groupstack;
        $this->defractorGroupstack();
        $this->run();
    }
    public function defractorGroupstack(){
        for($i = 0; $i < count($this->groupstack); $i++){
            foreach($this->groupstack[$i] as $grouptype => $value){
                switch($grouptype){
                    case "namespace":
                    case "prefix":
                        $this->uri = $value.$this->uri;
                    break;
                    case "domain":
                        $this->domain = $value;
                    break;
                }
            }  
        }
    }
    public function countSectionVariables(){
        $count = 0;
        foreach($this->getSections() as $section){
            if($section->isValue()){
                $count++;
            }
        }
        return $count;
    }
    public function setSectionCount(){
        return count($this->getSections());
    }
    public function setSections(){
        return $this->explodeIntoSections("/",$this->uri,"uri");
    }
    public static function explodeIntoSections($divider,$string,$type){
        $arr = [];
        foreach(array_filter(array_values(explode($divider,$string))) as $section){ 
            if(!empty($section)){
                $arr[] = new Section($section);
            }
        }
        return $arr;
    }
    public function getRouter(){
        return Cleverload::getInstance()->request->router;
    }
    public function getParameters(){
        return $this->parameters;
    }
    public function addParameter($parameter){
        $this->parameters[] = $parameter;
        return $this;
    }
    public function addParameters($parameters){
        $this->parameters = [];
        foreach($parameters as $key => $value){
            $this->parameters[$key] = $value;
        }
        return $this;
    }
    public function setParameters(){
        foreach($this->sections as $section){
            if($section->isValue()){
                array_push($this->parameters,$section->clean());
            }
        }
        return $this;
    }
    public function setParametersAsGet(){
        foreach($this->parameters as $key => $value){
            $_GET[$key] = $value;
        }
        return $_GET;
    }
    public function load(){
        if(is_callable($this->action)){
            return $this->callFunction($this->action,$this->getParameters());   
        }    
        return $this->loadFile();
    }

    public function callFunction($func = null,$values = []){
        return $func(...array_values($values));
    }
    public function loadFile(){
        if(Cleverload::getInstance()->template){
            return $this->getRouter()->response->setBody((new Template($this))->load());
        }
    }
    public function isValid(){
        if(preg_match("/[a-zA-Z\/}{]*/", $path)){
            return true;
        }
        return false;
    }
    public function getURI(){
        return $this->URI;
    }
    public function getSectionCount(){
        return $this->sectioncount;
    }
    public function setAction($action){
        if(is_callable($action)){
            $this->action = $action;
        }else{
            $this->file = Cleverload::getInstance()->getViewDir()."/".$action;
        }
    }
    public function getAction(){
        return $this->action;
    }
    public function getFile(){
        return $this->file;
    }
    public function getDomain(){
        return $this->domain;
    }
    public function setDomain($domain){
        $this->domain = $domain;
        return $this;
    }
    public function getSection($number){
        if(array_key_exists($number, $this->getSections())){
            return $this->getSections()[$number];
        }
        return null;
    }
    public function getSections(){
        return $this->sections;
    }
    public function hasMethod($method){
        if(in_array($method, $this->getMethods())){
            return true;
        }
        return false;
    }
    public function getMethods(){
        return $this->methods;
    }
    public function getWhere(){
        return $this->wheres;
    }
    public function getIf(){
        return $this->if;
    }
    public function __call($function,$args){
        return Cleverload::getInstance()->request->router->call($function,$args);
    }
    public static function __callStatic($function,$args){
        return Cleverload::getInstance()->request->router->call($function,$args);
    }
}
?>