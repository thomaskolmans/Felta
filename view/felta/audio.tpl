<?php
namespace lib\Post;

use lib\Felta;

class Audio extends Post{
    
    protected $name = "post_audio";

    
    public function __construct(){
        $this->sql = Felta::getInstance()->sql;
        $this->create([
            "id" => "int auto_increment",
            "artist" => "varchar(255)",
            "name" => "varchar(255)",
            "sound1" => "varchar(255)",
            "sound2" => "varchar(255)",
            "sound3" => "varchar(255)",
            "posted" => "DateTime"
        ]);
    }
    public function addAudio($artist,$name,$sound1,$sound2 = null,$sound3 = null){
        $now = new DateTime();
        $now = $now->format("Y-m-d H:i");
        $audios = $this->upload_audio([$sound1,$sound2,$sound3]);
        $this->add([0,$artist,$name,$audios[0],$audios[1],$audios[2],$now]);
    }

    public function upload_audio($sounds){
        $paths = [];
        foreach($sounds as $sound){
            if($sound === null) $paths[] = null; continue;
            $file = new File($sound);
            if($file->upload()){
                $paths[] = $file->getRelativeDest();
            }
        }
        return $paths;
    }

    public function getAll(){
        return $this->select("*",[]);
    }
}
?>