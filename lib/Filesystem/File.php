<?php
namespace lib\Filesystem;

class File{

    public $info;
    public $path;
    public $files = [];
    public $accepted = [];
    public $dest;
    public $relative_dest;

    private $name;
    private $tmp_name;
    private $basedir;
    private $base;
    private $succes;

    protected $image = ["jpg","png","gif","tiff"];
    protected $video = ["mp4","avi","mov"];
    protected $audio = ["wav","mp3"];

    public function __construct($file,$name = ""){
        $this->files = $file;
        $this->path = $this->files["name"];
        $this->info = pathinfo($this->path);
        if($name != ""){
            $this->name = $name;
        }else{
            $this->name = $this->createName();
        }
        $this->basedir = str_replace(ROOT, "", $_SERVER["DOCUMENT_ROOT"]);
        $this->base = $this->getDefaultDir();
    }
    
    public function getFiletype(){
        if(in_array($this->getExtension, $this->image)){
            return "image";
        }
    }
    public function delete(){
        unlink($this->path);
        return $this;
    }
    public function move($to){
        rename($this->path,$to);
        return $this;
    }
    public function upload($destination = null, $onsucces = null){
        if($destination != null){
            $this->dest =  $this->basedir.$this->base."/".$destination."/".$this->name.".".$this->getExtension();
            $this->relative_dest = $this->base."/".$destination."/".$this->name.".".$this->getExtension();
        }else{
            $this->dest = $this->basedir.$this->base."/".$this->name.".".$this->getExtension();
            $this->relative_dest = $this->base."/".$this->name.".".$this->getExtension();
        }
        if(move_uploaded_file($this->getTmpFile($this->getFilename()), $this->dest)){
            $this->succes = true;
            if($onsucces != null){
                $onsucces();
            }
        }else{
            $this->succes = false;
        }
        return $this->succes;
    }

    public function createName($length = 10){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function getDefaultDir(){
        $dir = \lib\Felta::getInstance()->settings->get("default_dir");
        if(!file_exists(ROOT.$dir)){
            mkdir(ROOT.$dir);
        }
        return $dir;
    }
    public function getDestination(){
        return $this->dest;
    }
    public function getRelativeDest(){
        return $this->relative_dest;
    }
    public function getDir(){
        return $this->info["dirname"];
    }
    public function getBasename(){
        return $this->info["basname"];
    }
    public function getExtension(){
        return $this->info["extension"];
    }
    public function getFilename(){
        return $this->info["filename"];
    }
    public function getTmpFile(){
        return $this->files["tmp_name"];
    }
    public function setAccepted($array){
        $this->accepted = $array;
    }
    public function addAccepted($value){
        array_push($this->accepted,$value);
    }
    public function is($type){
        if($this->getFiletype() == $type){
            return true;
        }
        return false;
    }
}
?>