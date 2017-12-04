<?php
use lib\Cleverload;
use lib\Felta;
use lib\Routing\Route;
use lib\Post\Edit;
use lib\Helpers\Input;

use lib\Post\Agenda;
use lib\Post\News;

$felta = Felta::getInstance();
$user = $felta->user;
$edit = new Edit();
$agenda = new Agenda();
$news = new News();

Route::group(["namespace" => "/felta"],function() use ($user,$edit,$felta,$agenda,$news){
    if(!$felta->user->hasSession()){
        Route::get("/login","felta/login.tpl")->when(!$felta->user->hasSession())->primary();
        Route::any("/forgot","felta/reset.tpl");
        Route::get("/forgot/code/{code}","felta/reset.tpl");

        Route::post("/login",function() use ($user){
            if($user->login($_POST["username"],$_POST["password"],$_POST["remember"])){
                echo json_encode(["logged_in" => true,"message" =>"Succesfull login"]);
            }else{
                echo json_encode(["logged_in" => false,"message" =>"Incorrect username and/or password"]);
            }
        });

        Route::get("/user/verify/{key}",function($key) use ($felta){
            $user = $felta->user;
            $user->verifyVerification($key);
            header("Location: /felta");
        });  
    }


    if($felta->user->hasSession()){
        
        /* logged in pages */
        Route::any("/dashboard","felta/dashboard.tpl")->when($felta->user->hasSession())->primary();
        Route::get("/editor","felta/editor.tpl");
        Route::get("/social","felta/social.tpl");
        Route::get("/settings","felta/settings.tpl");
        Route::get("/agenda","felta/agenda.tpl");
        Route::get("/agenda/id/update","felta/agenda.tpl");
        Route::get("/news","felta/news.tpl");
        Route::get("/blog","felta/blog_main.tpl");
        Route::get("/statistics","felta/statistics.tpl");

        Route::post("/settings",function() use ($felta){
            $user = $felta->user;
            if(isset($_POST['addition'])){
                $username = $_POST['username'];
                $email = $_POST['email'];
                if($user->createWithPassword($username,$email)){
                    echo "<div class='message'>User had been added!</div>";
                }else{
                    echo "<div class'message'>Sorry, something went wrong</div>";
                }
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
            }
            if(isset($_POST['general'])){
                $felta->settings->set('website_url',$_POST['website_url']);
                $felta->settings->set('website_name',$_POST['website_name']);
                $felta->settings->set('default_dir',$_POST['default_dir']);
            }
        });
        Route::post("/settings/delete/user",function() use ($felta){
            $user = $felta->user;
            $user->delete($_POST['id']);
        });
        Route::post("/news",function() use ($news){
            $agenda->put($_POST['title'],$_POST['description'],null,new \DateTime($_POST['date']));
            header("Location: /Felta/news");
        });
        Route::post("/news/update",function() use($news){
            $id = $_POST['id'];
            $news->update('title',['id' => $id],$_POST['title']);
            $news->update('description',['id' => $id],$_POST['description']);
            $date = new \DateTime($_POST['date']);
            $news->update('date',['id' => $id],$date->format("Y-m-d H:i:s"));
            header("Location: /Felta/news");
        });
        Route::get("/news/delete/{id}",function($id) use ($news){
            $news->delete(["id"=>$id]);
            header("Location: /Felta/news");
        });

        Route::post("/agenda",function() use ($agenda){
            $agenda->put($_POST['title'],$_POST['description'],null,$_POST['location'],new \DateTime($_POST['date']));
            header("Location: /Felta/agenda");
        });
        Route::post("/agenda/update",function() use($agenda){
            $id = $_POST['id'];
            $agenda->update('title',['id' => $id],$_POST['title']);
            $agenda->update('description',['id' => $id],$_POST['description']);
            $agenda->update('location',['id' => $id],$_POST['location']);
            $date = new \DateTime($_POST['date']);
            $agenda->update('date',['id' => $id],$date->format("Y-m-d H:i:s"));
            header("Location: /Felta/agenda");
        });
        Route::get("/agenda/delete/{id}",function($id) use ($agenda){
            $agenda->delete(["id"=>$id]);
            header("Location: /Felta/agenda");
        });
        Route::get("/logout",function(){
            $user = Felta::getInstance()->user;
            $user->logout();
            header("Location: /Felta/login");
        });
        Route::get("/edit/id/{id}/lang/{language}",function($id,$language) use ($edit){
            echo $edit->get($id,$language);
        });
        Route::post("/edit",function() use ($edit){
            $text = $_POST["text"];
            $id = $_POST["id"];
            $language = $_POST["language"];
            $edit->setText($id,$language,$text);
        });
        Route::post("/edit/image",function() use ($edit){
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
        Route::delete("/language/{lng}",function($lng) use ($edit){
            $edit->language->remove($lng); 
        });
    }
});

?>