<?php
namespace lib\shop\order;

use lib\Felta;
use lib\helpers\UUID;

class Customer {
    
    private $sql;

    public $id;
    public $email;
    public $firstname;
    public $lastname;

    public $address;

    public $isBusiness = false;
    public $bName;
    public $account = false;

    private $password;
    private $created;

    public function __construct($id,$firstname,$lastname,$email, $address,$isBusiness,$bName,$account = false){
        $this->sql = Felta::getInstance()->getSQL();

        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->address = $address;
        $this->isBusiness = $isBusiness;
        $this->bName = $bName;
        $this->account = $account;
    }

    public static function fromResult($result, $address) {
        return new Customer(
            $result["id"],
            $result["firstname"],
            $result["lastname"],
            $result["email"],
            $address,
            $result["isBusiness"],
            $result["bName"],
            $result["account"]
        );
    }

    public function save(){
        $this->sql->insert("shop_customer",[
            $this->id,
            $this->firstname,
            $this->lastname,
            $this->email,
            $this->isBusiness,
            $this->bName,
            $this->account
        ]);
        $this->address->save();
    }

    public function update(){
        $this->sql->update("firstname", "shop_customer", ["id" => $this->id], $this->firstname);
        $this->sql->update("lastname", "shop_customer", ["id" => $this->id], $this->lastname);
        $this->sql->update("email", "shop_customer", ["id" => $this->id], $this->email);
        $this->sql->update("isBusiness", "shop_customer", ["id" => $this->id], $this->isBusiness);
        $this->sql->update("bName", "shop_customer", ["id" => $this->id], $this->bName);
        $this->sql->update("account", "shop_customer", ["id" => $this->id], $this->account);

    }

    public function delete(){
        $this->sql->delete("shop_customer",["id" => $this->id]);
        $this->sql->delete("shop_customer_address",["id" => $this->id]);
        $this->sql->delete("shop_customer_account",["id" => $this->id]);
    }

    public static function get($id){
        $sql = Felta::getInstance()->getSQL();
        $address = CustomerAddress::get($id);
        $result = $sql->select("*","shop_customer",["id" => "$id"])[0];
        return Customer::fromResult($result, $address);
    }

    public static function exists($id){
        return Felta::getInstance()->getSQL()->exists("shop_customer",["id" => $id]);
    }

    public static function create($firstname,$lastname,$email,$street,$number,$zipcode,$city,$country,$isBusiness,$bName,$account = false){
        $id = UUID::generate(10);
        $address = new CustomerAddress($id,$street,$number,$zipcode,$city,$country);
        $customer = new Customer($id,$firstname,$lastname,$email,$address,$isBusiness,$bName);
        return $customer;
    }

    public function register($email,$password){
        if(!$this->sql->exists("shop_customer_account",["email" => $email])){
            $hash = password_hash($password,PASSWORD_DEFAULT);
            $now = new \DateTime();
            $this->sql->insert($this->table,[$this->id,$email,$hash,$now->format("Y-m-d H:i:s")]);
            $this->account = true;
            return true;
        }
        return false; 
    }
    
    public static function login($email,$password){
        $sql = Felta::getInstance()->getSQL();
        $table = "shop_customer_account";
        $id = $sql->select("id",$table,["email" => $email]);
        $hash = $sql->select("password",$table,["id" => $id]);

        if(password_verify($password,$hash)){
            $customer = Customer::get($id);
            $_SESSION["shop_account"] = $id;
            return true;
        }
        return false;
    }

    public function logout(){
        unset($_SESSION["shop_account"]);
    }
}

?>