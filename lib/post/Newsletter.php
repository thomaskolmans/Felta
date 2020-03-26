<?php
namespace lib\post;

use lib\Felta;
use \DateTime;

class Newsletter {

    public static function subscribe($email){
        if(!Newsletter::isSubscribed($email)){
            $date = new DateTime();
            Felta::getInstance()->getSQL()->insert("newsletter_subscription",[
                0,
                $email,
                $date->format("Y-m-d H:i:s")
            ]);
        }
    }

    public static function unsubscribe($email){
        if(Newsletter::isSubscribed($email)){
            Felta::getInstance()->getSQL()->delete("newsletter_subscription",["email" => $email]);
        }
    }

    public static function isSubscribed($email){
        return Felta::getInstance()->getSQL()->exists("newsletter_subscription",["email" => $email]);
    }
} 

?>