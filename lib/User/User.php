<?php
namespace lib\User;

use lib\Felta;
use lib\Helpers\Email;
use lib\Helpers\Value;

class User extends Felta{

    protected $table = "users";
    public $verify_needed = true;

    public $sql;
    private $csrf;

    private $id;
    private $username;
    private $dname;
    private $email;
    private $online;

    private $permission;

    private $password;
    private $session_key;

    public $session;
    public $fb;

    public function __construct($sql, $verify_needed = true, $table = null){
        $this->sql = $sql;
        $this->createTables();
        $this->verify_needed = $verify_needed;
        if($table !== null){
            $this->table = $table;
        }
        if(!isset($_SESSION["csrf"])){
            $this->csrf = base64_encode( openssl_random_pseudo_bytes(32));
        }else{
            $this->csrf = $_SESSION["csrf"];
        }
        $this->admin();
    }

    public function admin(){
        if($this->sql->count($this->table,[]) <= 0){
            $email = \lib\Felta::getConfig("email");
            $username = \lib\Felta::getConfig("username");
            $this->createWithPassword($username,$email);
            return $this;
        }
    }

    public function exists($username,$email){
        if($this->usernameExists($username) && $this->emailExists($email)){
            return true;
        }
        return false;
    }

    public function create($username,$dname,$password,$email,$active = false){
        $sql = $this->sql;
        if(!$this->exists($username,$email)){
            $hash = password_hash($password,PASSWORD_DEFAULT);
            $sql->insert($this->table,array(0,$username,$dname,$hash,$email,false,$active));
            if($this->verify_needed){
                $this->sendVerification(null,$email,$sql->select("id",$this->table,["email" => $email]),$password);
            }
            return true;
        }
        return false;
    }

    public function createWithPassword($username,$email){
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 12; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        $password = implode($pass);
        if(!$this->exists($username,$email)){
            if($this->create($username,$username,$password,$email)){
                return true;
            }
        }
        return false;
    }

    public function forgot($input,$time = 1800000){
        if($this->isEmail($input)){
            $id = $this->sql->select("id",$this->table,array("email" => $input));
        }else{
            $id = $this->sql->select("id",$this->table,array("username" => $input));
        }
        if(!$this->sql->exists($this->table."_forgot", ["id" => $id])){
            $key = $this->uuid();
            $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/felta/forgot/code/".$key;
            $adress = $this->sql->select("email",$this->table,["id" => $id]);
            $email = new Email();
            $email->html(true);
            $email->setTo($adress);
            $email->setFrom(Felta::getConfig("smtp")['username']);
            $email->setSubject("Account recovery");
            $email->setMessage(str_replace("{url}", $url, $email->load("emails/forgot.html")));
            $email->send();

            $now = new \DateTime();
            $now = $now->format("Y-m-d h:i:s");

            $until = null;
            if($time !== null){
                $until = new \DateTime();
                $until->setTimestamp(time() + $time);
                $until = $until->format("Y-m-d h:i:s"); 
            }
            $this->sql->insert($this->table."_forgot",[$id,$key,$now,$until]);
        }
    }

    public function verifyForgot($key){
        $now = new \DateTime();
        if($this->sql->exists($this->table."_forgot",["key" => $key])){
            $dtime = new \DateTime($this->sql->select("expire",$this->table."_forgot",["key" => $key]));
            if($now->format("Y-m-d H:i:s") <= $dtime->format("Y-m-d H:i:s")){
                return true;
            }
        }
        return false;
    }

    public function delete($id = null){
        if($id === null)
            $this->id = $id;
        $this->sql->delete($this->table,array("id" => $id));
        $this->sql->delete($this->table."_remember",array("id" => $id));
        $this->sql->delete($this->table."_verification",array("id" => $id));
        return $this;
    }

    public function login($input,$password,$remember = false){
        $this->password = $password;
        if($this->isEmail($input)){
            $this->email = $input;
            $this->username = $this->sql->select("username",$this->table,array("email" => $this->email));
        }else{
            $this->username = $input;
            $this->email = $this->sql->select("email",$this->table,array("username" => $this->username));
        }
        $this->id = $this->sql->select("id",$this->table,array("username" => $this->username));
        $hash = $this->sql->select("password",$this->table,["id" => $this->id]);
        if($this->verify_needed){
            $verified = $this->sql->select("active",$this->table,["id" => $this->id]);
            if(!$verified)
                return false;
        }
        if(password_verify($this->password,$hash)){
            $this->setSession();
            $this->session = true;
            if($remember){
                $this->remember();
            }
        }
        return $this->session;
    }
    public function remember($time = 31556926){
        $until = time() + $time;
        $untildate = new \DateTime();
        $untildate->setTimestamp($until);
        $untildate = $untildate->format("Y-m-d H:i:s");
        $now = new \DateTime();
        $now = $now->format("Y-m-d H:i:s");

        $this->session_key = $this->uuid();
        $key = $this->session_key;
        if(!$this->sql->exists($this->table."_remember",array("id" => $this->id))){
            $this->sql->insert($this->table."_remember",array(
                $this->id,
                $key,
                $now,
                $untildate
                ));
            setcookie("id",$this->id,$until,"/");
            return setcookie("session_key",$key,$until,"/");
        }
        return $this;
    }

    public function logout(){
        $this->sql->delete($this->table."_remember",array("id" => $_SESSION["user"][0]));
        unset($_SESSION["user"]);
        setcookie("session_key","",time() - 10,"/");
        return $this;
    }

    public function sendVerification($time = null,$adress,$id,$password){
        if(!$this->sql->exists($this->table."_verification", ["id" => $id])){
            $verifycode = $this->uuid();
            $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/felta/user/verify/".$verifycode;
            $username = $this->sql->select("username",$this->table,["id" => $id]);

            $email = new Email();
            $email->html(true);
            $email->setTo($adress);
            $email->setFrom(Felta::getConfig("smtp")['username']);
            $email->setSubject("Your ".Felta::getConfig("website_name"). " account");
            $email->setMessage(str_replace(["{url}","{password}","{username}"], [$url,$password,$username], $email->load("emails/welcome.html")));
            $email->send();

            $now = new \DateTime();
            $now = $now->format("Y-m-d h:i:s");

            $until = null;
            if($time !== null){
                $until = new \DateTime();
                $until->setTimestamp(time() + $time);
                $until = $until->format("Y-m-d h:i:s"); 
            }
            $this->sql->insert($this->table."_verification",[$id,$adress,$verifycode,$now,$until]);
        }
    }
    public function verifyVerification($key){
        if($this->sql->exists($this->table."_verification",["key" => $key])){
            $now = new \DateTime();
            $until = $this->sql->select("expire",$this->table."_verification",["key" => $key]);
            if($until !== null){
                $until = new \DateTime($until);
                if($until > $now){
                    return false;
                }
            }
            $this->sql->update("active",$this->table,["id" => $this->sql->select("id",$this->table."_verification",["key" => $key])],true);
            $this->sql->delete($this->table."_verification",["key" => $key]);
            return true;
        }
        return false;
    }
    public function hasSession(){
        if(!isset($_SESSION["user"])){
            if(isset($_COOKIE["session_key"])){
                $id = $_COOKIE["id"];
                $db_key = $this->sql->select("key",$this->table."_remember",array("id" => $id));
                if($db_key == $_COOKIE["session_key"]){
                    $this->setSessionFromId($id);
                    return true;
                }
                return false;
            }
        }else{
            return true;
        }
        return false;
    }
    public function dnameExists($dname){
        return $this->sql->exists($this->table,array("dname" => $dname));
    }
    public function usernameExists($username){
        return $this->sql->exists($this->table,["username" => $username]) ;
    }
    public function emailExists($email){
        return $this->sql->exists($this->table,array("email" => $email));
    }
    public function uuid(){
        return md5(microtime().rand());
    }
    public function setSessionFromId($id){
        $this->id = $id;
        $this->username = $this->sql->select("username",$this->table,["id" => $this->id]);
        $this->email = $this->sql->select("email",$this->table,["id" => $this->id]);
        return $this->setSession();
    }
    public function setSession(){
        $_SESSION["user"] = [$this->id,$this->username,$this->email];
        return $_SESSION;
    }
    public function getSession(){
        return $_SESSION["user"];
    }
    public function getAll(){
        return $this->sql->select("*",$this->table,[]);
    }
    public function isOnline(){
        return $this->online;
    }
    public function hasActiveSession(){
        return $this->session;
    }
    public function isLoggedIn(){
        return $this->session;
    }
    private function isEmail($email){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
        }
        return false;
    }
    public function resetPassword($old,$new,$repeat){
        $id = $_SESSION["user"][0];
        $hash = $this->sql->select("password",$this->table,["id" => $id]);
        if(password_verify($old,$hash)){
            if($new === $repeat){
                $this->sql->update("password",$this->table,["id" => $id],password_hash($new,PASSWORD_DEFAULT));
                return true;
            }
        }
        return false;
    }
    public function recoverPassword($key,$newpassword,$repeatpassword){
        if($this->verifyForgot($key)){
            if($newpassword === $repeatpassword){
                $id = $this->sql->select("id",$this->table."_forgot",["key" => $key]);
                $this->sql->delete($this->table."_forgot",["key" => $key]);
                $this->sql->update("password",$this->table,["id" => $id],password_hash($newpassword,PASSWORD_DEFAULT));
                return true;
            }
        }
        return false;
    }
    public function setPassword($password){
        $this->password = $password;
        return $this;
    }
    public function getPassword(){
        return $this->password;
    }
    public function setSessionkey($key){
        $this->session_key = $key;
    }
    public function getSessionkey(){
        return $this->session_key;
    }
    public function settable($table){
        $this->$table = $table;
        return $this;
    }
    public function gettable(){
        return $this->$table;
    }
    public function createTables(){
        if(!$this->sql->exists($this->table,array())){
            $this->sql->create($this->table,array(
                "id" => "int auto_increment",
                "username" => "varchar(255)",
                "dname" => " varchar(255)",
                "password" => "varchar(550)",
                "email" => "varchar(255)",
                "online" => "boolean",
                "active" => "boolean"
            ),"id");
            $this->sql->create($this->table."_remember",array(
                "id" => "int",
                "key" => "varchar(255)",
                "start" => "DateTime",
                "expire" => "DateTime"
            ),"id");
            $this->sql->create($this->table."_history",[
                "id" => "int",
                "uuid" => "int",
                "from" => "DateTime",
                "until" => "DateTime"
            ],"id");
            $this->sql->create($this->table."_statistics",[
                "id" => "int",
                "sign_date" => "DateTime",
                "sign_place" => "varchar(100)",
                "sign_ip" => "varchar(50)"
            ],"id");
            $this->sql->create($this->table."_permission",[
                "id" => "int",
                "permission" => "int"
                ],"id");
            $this->sql->create($this->table."_password_history",array(
                "id" => "int",
                "from" => "varchar(255)",
                "to" => "varchar(255)",
                "date" => "DateTime"
                ),"id");
            $this->sql->create($this->table."_verification",array(
                "id" => "int",
                "email" => "varchar(255)",
                "key" => "varchar(255)",
                "start" => "DateTime",
                "expire" => "DateTime"
                ),"id");
            $this->sql->create($this->table."_forgot",array(
                "id" => "int",
                "key" => "varchar(255)",
                "start" => "DateTime",
                "expire" =>  "DateTime"
                ),"id");
            return true;
        }
        return false;
    }
}
?>