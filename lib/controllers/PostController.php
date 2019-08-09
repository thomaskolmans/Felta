<?php
namespace lib\controllers;

use lib\Felta;
use lib\post\Edit;
use lib\post\Agenda;
use lib\post\News;

class PostController {

    /**
     * News
     */
    public static function ADD_NEWS(){
        $news = new News();
        $news->put(
            htmlspecialchars($_POST["title"], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($_POST["description"], ENT_QUOTES, 'UTF-8'),
            null,
            new \DateTime($_POST['date'])
        );
        echo json_encode(["success" => "News has been succesfully added"]);
    }

    public static function UPDATE_NEWS(){
        $news = new News();
        $id = $_POST['id'];
        $news->update('title',['id' => $id], htmlspecialchars($_POST["title"], ENT_QUOTES, 'UTF-8'));
        $news->update('description',['id' => $id], htmlspecialchars($_POST["description"], ENT_QUOTES, 'UTF-8'));
        $date = new \DateTime($_POST['date']);
        $news->update('date',['id' => $id], $date->format("Y-m-d H:i:s"));
        echo json_encode(["success" => "News has been succesfully updated"]);
    }

    public static function DELETE_NEWS($id){
        $news = new News();
        $news->delete(["id" => $id]);
        echo json_encode(["success" => "News has been succesfully deleted"]);
    }

    /**
     * Agenda
     */
    public static function ADD_AGENDA(){
        $agenda = new Agenda();
        $agenda->put(
            htmlspecialchars($_POST["title"], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($_POST["description"], ENT_QUOTES, 'UTF-8'),
            null,
            htmlspecialchars($_POST["location"], ENT_QUOTES, 'UTF-8'),
            new \DateTime($_POST['from']),
            new \DateTime($_POST['until'])
        );
        echo json_encode(["success" => "Agenda item has been succesfully added"]);
        header("Location: /felta/agenda");
    }

    public static function UPDATE_AGENDA(){
        $agenda = new Agenda();
        $id = $_POST['id'];
        $agenda->update('title',['id' => $id],htmlspecialchars($_POST["title"], ENT_QUOTES, 'UTF-8'));
        $agenda->update('description',['id' => $id], htmlspecialchars($_POST["description"], ENT_QUOTES, 'UTF-8'));
        $agenda->update('location',['id' => $id], htmlspecialchars($_POST["location"], ENT_QUOTES, 'UTF-8'));
        $from = new \DateTime($_POST['from']);
        $until = new \DateTime($_POST['until']);
        $agenda->update('from',['id' => $id],$from->format("Y-m-d H:i:s"));
        $agenda->update('until',['id' => $id],$until->format("Y-m-d H:i:s"));
        echo json_encode(["success" => "Agenda item has been succesfully updated"]);
        header("Location: /felta/agenda");
    }

    public static function DELETE_AGENDA($id){
        $agenda = new Agenda();
        $agenda->delete(["id"=>$id]);
        echo json_encode(["success" => "Agenda item has been succesfully deleted"]);
        header("Location: /felta/agenda");
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
    public static function GET_TEXT($id, $language){
        $edit = new Edit();
        echo $edit->get($id,$language);
    }

    public static function SET_TEXT(){
        $edit = new Edit();
        $text = $_POST["text"];
        $id = $_POST["id"];
        $language = $_POST["language"];
        $edit->setText($id,$language,$text);
        echo json_encode(["success" => "You've succesfully edited the tekst"]);
    }

    public static function SET_IMAGE(){
        $edit = new Edit();
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
        $edit = new Edit();
        $edit->language->remove($language); 
        echo json_encode(["success" => "You've succesfully removed the language"]);
    }
}