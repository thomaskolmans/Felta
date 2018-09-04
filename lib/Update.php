<?php
namespace lib;

use lib\Cleverload;

class Update{

    public $version;
    public $new_version;

    protected $git;
    
    public function __construct($git){
        $this->setGit($git);
        $this->findUpdate();
    }

    public function findUpdate(){

    }
    
    protected function connect(){
        
    }

    public function setVersion(){
        $this->version = $version;
        return $this;
    }

    public function getVersion(){
        return $this->version;
    }

    public function setGit($url){
        $this->git = $url;
        return $this;
    }

    public function getGit(){
        return $this->git;
    }
}