<?php
namespace lib\helpers;

use lib\Helpers\Value;

class Input{

    public static $value = null;

    public static function get($name){
        $value = null;
        if(isset($_POST[$name])){
             $value = new Value($_POST[$name]) ;
        }else if(isset($_GET[$name])){
             $value = new Value($_GET[$name]);
        }
        return $value;
    }

    public static function value($name){
        if(isset($_POST[$name])){
            return $_POST[$name];
        }else if(isset($_GET[$name])){
            return $_GET[$name];
        }
        return null;
    }
    
    public static function exists($name){
        if(self::get($name) !=  null){
            return true;
        }
        return false;
    }
}