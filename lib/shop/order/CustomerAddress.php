<?php
namespace lib\shop\order;

use lib\Felta;
use lib\helpers\UUID;

class CustomerAddress{

    private $sql;
    private $id;

    private $street;
    private $number;
    private $zipcode;
    private $city;
    private $country;

    public function __construct($id, $street,$number,$zipcode,$city,$country){
        $this->sql = Felta::getInstance()->getSQL();

        $this->id = $id;
        $this->street = $street;
        $this->number = $number;
        $this->zipcode = $zipcode;
        $this->city = $city;
        $this->country = $country;
    }

    public static function create($id,$street,$number,$zipcode,$city,$country){
        return new CustomerAddress($id, $street,$number,$zipcode,$city,$country);
    }
    
    public static function get($id){
        $result = Felta::getInstance()->getSQL()->select("*","shop_customer_address",["id" => $id])[0];
        return CustomerAddress::fromResult($result);
    }

    public static function fromResult($result){
        return new CustomerAddress (
            $result["id"],
            $result["street"],
            $result["number"],
            $result["zipcode"],
            $result["city"],
            $result["country"]
        );
    }

    public function save(){
        $this->sql->insert("shop_customer_address",[
            $this->id,
            $this->street,
            $this->number,
            $this->zipcode,
            $this->city,
            $this->country
        ]);
    }

    public function setStreet($street){
        $this->street = $street;
        return $this;
    }
    public function getStreet(){
        return $this->street;
    }
    public function setNumber($number){
        $this->number = $number;
        return $this;
    }
    public function getNumber(){
        return $this->number;
    }
    public function setzipcode($zipcode){
        $this->zipcode = $zipcode;
        return $this;
    }
    public function getzipcode(){
        return $this->zipcode;
    }
    public function setCity($city){
        $this->city = $city;
        return $this;
    }
    public function getCity(){
        return $this->city;
    }
    public function setCountry($country){
        $this->country = $country;
        return $this;
    }
    public function getCountry(){
        return $this->country;
    }
}