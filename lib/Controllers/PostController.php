<?php
namespace lib\Controllers;

use lib\Felta;

$edit = new Edit();
$agenda = new Agenda();
$news = new News();

class PostController {

    /**
     * News
     */
    public static function ADD_NEWS(){
        $news = new News();
        $news->put($_POST['title'],$_POST['description'],null,new \DateTime($_POST['date']));
        echo json_encode(["success" => "News has been succesfully added"]);
    }

    public static function UPDATE_NEWS(){
        $news = new News();
        $id = $_POST['id'];
        $news->update('title',['id' => $id],$_POST['title']);
        $news->update('description',['id' => $id],$_POST['description']);
        $date = new \DateTime($_POST['date']);
        $news->update('date',['id' => $id],$date->format("Y-m-d H:i:s"));
        echo json_encode(["success" => "News has been succesfully updated"]);
    }

    public static function DELETE_NEWS($id){
        $news = new News();
        $news->delete(["id"=>$id]);
        echo json_encode(["success" => "News has been succesfully deleted"]);
    }

    /**
     * Agenda
     */
    public static function ADD_AGENDA(){
        $agenda = new Agenda();
        $agenda->put(
            $_POST['title'],
            $_POST['description'],
            null,
            $_POST['location'],
            new \DateTime($_POST['from']),
            new \DateTime($_POST['until'])
        );
        echo json_encode(["success" => "Agenda item has been succesfully added"]);
    }

    public static function UPDATE_AGENDA(){
        $agenda = new Agenda();
        $id = $_POST['id'];
        $agenda->update('title',['id' => $id],$_POST['title']);
        $agenda->update('description',['id' => $id],$_POST['description']);
        $agenda->update('location',['id' => $id],$_POST['location']);
        $from = new \DateTime($_POST['from']);
        $until = new \DateTime($_POST['until']);
        $agenda->update('from',['id' => $id],$from->format("Y-m-d H:i:s"));
        $agenda->update('until',['id' => $id],$until->format("Y-m-d H:i:s"));
        echo json_encode(["success" => "Agenda item has been succesfully updated"]);
    }

    public static function DELETE_AGENDA($id){
        $agenda = new Agenda();
        $agenda->delete(["id"=>$id]);
        echo json_encode(["success" => "Agenda item has been succesfully deleted"]);
    }

    /**
     * Blog
     */
    public static function ADD_BLOG(){
        
    }
    
    public static function UPDATE_BLOG(){

    }

    public static function DELETE_BLOG($id){

    }
    /**
     * Audio
     */
    public static function ADD_AUDIO(){

    }
    public static function UPDATE_AUDIO(){

    }
    public static function DELETE_AUDIO($id){

    }

    /**
     * Edit
     */
    public static function GET_TEKST($id, $language){
        $edit = Edit();
        echo $edit->get($id,$language);
    }

    public static function SET_TEKST(){
        $edit = Edit();
        $text = $_POST["text"];
        $id = $_POST["id"];
        $language = $_POST["language"];
        $edit->setText($id,$language,$text);
        echo json_encode(["success" => "You've succesfully edited the tekst"]);
    }

    public static function SET_IMAGE(){
        $edit = Edit();
        $w = Input::value("w");
        $h = Input::value("h");
        $x = Input::value("x1");
        $y = Input::value("y1");
        $x2 = Input::value("x2");
        $y2 = Input::value("y2");
        $id = Input::value("id");
        $lang = Input::value("language");
        $file =  new lib\Filesystem\File($_FILES["file_name"],null);
        $image = $file->getTmpFile();
        if($file->upload(null)){
            lib\Filesystem\type\Image::resize($file->getDestination(),$x,$y,$w,$h,$x2,$y2);
        }
        $edit->setText($id,$lang,$file->getRelativeDest());
        echo $file->getRelativeDest();
    }

    public static function DELETE_LANGUAGE($language){
        $edit->language->remove($lnlanguageg); 
        echo json_encode(["success" => "You've succesfully removed the language"]);
    }
}