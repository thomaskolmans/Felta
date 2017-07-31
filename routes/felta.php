<?php
use lib\Routing\Route;
use lib\Post\Edit;
use lib\Cleverload;
use lib\Felta;
use lib\Helpers\Input;

$felta = Felta::getInstance();
$edit = new Edit();
$host = $felta->getHost();

Route::group(["domain" => "felta.".$host],function() use ($edit,$felta){

    $agenda = new lib\Post\Agenda();

    Route::get("/felta/login","felta/login.tpl")->when(!$felta->user->hasSession())->primary();
    Route::post("/felta/login",function(){
        $user = Felta::getInstance()->user;
        if($user->login($_POST["username"],$_POST["password"],$_POST["remember"])){
            echo json_encode(["logged_in" => true,"message" =>"Succesfull login"]);
        }else{
            echo json_encode(["logged_in" => false,"message" =>"Incorrect username and/or password"]);
        }
    });
    Route::get("/felta/forgot","felta/reset.tpl");
    Route::get("/felta/forgot/code/{code}","felta/reset.tpl");

    if($felta->user->hasSession()){
        Route::get("/felta/editor","felta/editor.tpl");
        Route::get("/felta/social","felta/social.tpl");
        Route::get("/felta/settings","felta/settings.tpl");
        Route::post("/felta/settings",function() use ($felta){
            $user = $felta->user;
            var_dumP($_POST);
            if(isset($_POST['add-user'])){
                $username = $_POST['username'];
                $email = $_POST['email'];
                if($user->createWithPassword($username,$email)){
                    header("Location: /Felta/settings");
                }
                header("Location: /Felta/settings");
            }
            if(isset($_POST['changepassword'])){
              $old = $_POST['old_password'];
              $new = $_POST['new_password'];
              $repeat = $_POST['repeat_new_password'];
              if($user->resetPassword($old,$new,$repeat)){
                echo "Password has changed";
              }else{
                echo "Either wrong password or new passwords didn't match";
              }
              header("Location: /Felta/settings");
            }
        });
        Route::get("/felta/agenda","felta/agenda.tpl");
        Route::get("/felta/agenda/id/update","felta/agenda.tpl");
        Route::get("/felta/news","felta/news.tpl");
        Route::get("/felta/blog","felta/blog_main.tpl");
        Route::post("/felta/agenda",function() use ($agenda){
            var_dump(new \DateTime($_POST['date']));
            $agenda->new($_POST['title'],$_POST['description'],null,$_POST['location'],new \DateTime($_POST['date']));
            header("Location: /Felta/agenda");
        });
        Route::post("/felta/agenda/update",function() use($agenda){
            $id = $_POST['id'];
            $agenda->update('title',['id' => $id],$_POST['title']);
            $agenda->update('description',['id' => $id],$_POST['description']);
            $agenda->update('location',['id' => $id],$_POST['location']);
            $date = new \DateTime($_POST['date']);
            $agenda->update('date',['id' => $id],$date->format("Y-m-d H:i:s"));
            header("Location: /Felta/agenda");
        });
        Route::get("/felta/agenda/delete/{id}",function($id) use ($agenda){
            $agenda->delete(["id"=>$id]);
            header("Location: /Felta/agenda");
        });
        Route::any("/felta/dashboard","felta/dashboard.tpl")->when($felta->user->hasSession())->primary();
        Route::get("/felta/logout",function(){
            $user = Felta::getInstance()->user;
            $user->logout();
            header("Location: /Felta/login");
        });
        Route::get("/felta/edit/id/{id}/lang/{language}",function($id,$language) use ($edit){
            echo $edit->get($id,$language);
        });
        Route::post("/felta/edit",function() use ($edit){
            $text = $_POST["text"];
            $id = $_POST["id"];
            $language = $_POST["language"];
            $edit->setText($id,$language,$text);
        });
        Route::post("/felta/edit/image",function() use ($edit){
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
        });
        Route::delete("/felta/language/{lng}",function($lng) use ($edit){
            $edit->language->remove($lng); 
        });
    }
});

?>