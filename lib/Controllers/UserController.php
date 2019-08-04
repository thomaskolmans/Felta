<?php
namespace lib\Controllers;

use lib\Felta;

class UserController {

    public static function REGISTER(){
        $user = Felta::getInstance()->user;
    }

    public static function LOGIN(){
        $user = Felta::getInstance()->user;
        if($user->login($_POST["username"],$_POST["password"],$_POST["remember"])){
            echo json_encode(["logged_in" => true, "message" =>"Succesfull login"]);
        }else{
            echo json_encode(["logged_in" => false, "message" =>"Incorrect username and/or password"]);
        }
    }

    public static function VERIFY_EMAIL($key){
        $user = Felta::getInstance()->user;
        $user->verifyVerification($key);
        header("Location: /felta");
    }

    public static function VERIFY_RESET_KEY($token){
        $user = Felta::getInstance()->user;
        if($user->verifyForgot($token)){
            return json_encode(["success" => "Key is valid"]);
        }
        return json_encode(["error" =>  "Key is invalid"]);
    }

    public static function RESET_PASSWORD(){
        $user = Felta::getInstance()->user;
        $user->recoverPassword($_POST["token"],$_POST["password"],$_POST["repeatpassword"]);
        return json_encode(["success", "Password has been reset"]);
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
            $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
            $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
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
            $felta->settings->set('website_url', htmlspecialchars($_POST['website_url'], ENT_QUOTES, 'UTF-8'));
            $felta->settings->set('website_name',htmlspecialchars($_POST['website_name'], ENT_QUOTES, 'UTF-8'));
            $felta->settings->set('default_dir',htmlspecialchars($_POST['default_dir'], ENT_QUOTES, 'UTF-8'));
            echo json_encode(["success" => "Has succesfully saved"]);
        }
    }

}
?>