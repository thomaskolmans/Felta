<?php
namespace lib\Controllers;

use lib\Felta;

class UserController {

    public static function REGISTER(){
        
    }

    public static function LOGIN(){
        if($user->login($_POST["username"],$_POST["password"],$_POST["remember"])){
            echo json_encode(["logged_in" => true, "message" =>"Succesfull login"]);
        }else{
            echo json_encode(["logged_in" => false, "message" =>"Incorrect username and/or password"]);
        }
    }

    public static function LOGOUT(){
        $user = Felta::getInstance()->user;
        $user->logout();
        echo json_encode(["success" => "You have successfully logged out"]);
    }

    public static function UPDATE_SETTINGS(){
        $felta = Felta::getInstance();
        $user = $felta->user;
        if(isset($_POST['addition'])){
            $username = $_POST['username'];
            $email = $_POST['email'];
            if($user->createWithPassword($username,$email)){
                echo json_encode(["success" => "User had been added!"]);
            }else{
                echo json_encode(["error" => "Sorry, something went wrong"]);
            }
        }
        if(isset($_POST['changepassword'])){
            $old = $_POST['old_password'];
            $new = $_POST['new_password'];
            $repeat = $_POST['repeat_new_password'];
            if($user->resetPassword($old,$new,$repeat)){
                echo json_encode(["success" => "Password has succesfully changed"]);
            }else{
                echo json_encode(["error" => "Either wrong password or new passwords didn't match"]);
            }
        }
        if(isset($_POST['general'])){
            $felta->settings->set('website_url',$_POST['website_url']);
            $felta->settings->set('website_name',$_POST['website_name']);
            $felta->settings->set('default_dir',$_POST['default_dir']);
            echo json_encode(["success" => "Has succesfully saved"]);
        }
    }

}
?>