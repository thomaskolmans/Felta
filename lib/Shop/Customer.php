<?php
namespace lib\Shop;

class Customer{
    
    public $firstname;
    public $lastname;
    public $address;

    public $email;
    private $password;
    
    public function __construct($firstname,$lastname,CustomerAdress $address){

    }
}

?>