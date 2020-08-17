<?php
namespace lib\post;

use lib\Felta;
use \DateTime;

class Newsletter {

    private $sql; 

    private $id;
    private $name;

    function __constructor(
        $id,
        $name
    ) {
        $this->sql = Felta::getInstance()->getSQL();

        $this->id = $id;
        $this->name = $name;
    }

    public function subscribe($email){
        if(!$this->isSubscribed($email)){
            $date = new DateTime();
            $this->sql
                ->insert("newsletter_subscription",[
                0,
                $email,
                $date->format("Y-m-d H:i:s")
            ]);
        }
    }

    public function unsubscribe($email){
        if($this->isSubscribed($email)){
            $this->sql->delete(
                "newsletter_subscription",
                ["newsletter" => $this->id, "email" => $email]
            );
        }
    }

    public function isSubscribed($email){
        return $this->sql->exists(
            "newsletter_subscription",
            ["newsletter" => $this->id, "email" => $email]
        );
    }
}
