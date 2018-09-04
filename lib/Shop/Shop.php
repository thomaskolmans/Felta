<?php
namespace lib\Shop;

use \DateTime;
use \lib\Felta;
use \Stripe\Stripe;
use \lib\Helpers\UUID;


class Shop{
    
    private $sql;

    public $id;
    public $name;
    public $created;

    private $paypalKey;
    private $stripeKey;

    private $paypal;
    private $stripe;

    public static $instance;

    public function __construct($id,$name,$created){
        $this->sql = Felta::getInstance()->getSQL();

        $this->id = $id;
        $this->name = $name;
        $this->created = $created;

        $this->createTables();
        self::$instance = $this;
        if(!isset($_COOKIE["SCID"])){
            $shoppingcart = Shoppingcart::create();
            $_COOKIE["SCID"] = $shoppingcart->getId();
            $shoppingcart->save();
        }
    }
    public static function getInstance(){
        if(isset(self::$instance)){
            return self::$instance;
        }
        return null;
    }
    public static function create($name,$stripe,$paypal){
        $sql = Felta::getInstance()->getSQL();
        if($sql->exists("shop",[])){
            if($sql->exists("shop",["name" => $name])){
                return Shop::get($name,$stripe,$paypal);
            }else{
                return Shop::generate($name,$stripe,$paypal);
            }
        }else{
            return Shop::generate($name,$stripe,$paypal);
        }

    }
    public static function generate($name,$stripe,$paypal){
        $id = UUID::generate(10);
        $shop = new Shop($id,$name,new \DateTime());
        $shop->setStripeKey($stripe);
        $shop->setPaypalKey($paypal);
        $shop->save();
        return $shop;
    }
    public static function get($name,$stripe,$paypal){
        $result = Felta::getInstance()->getSQL()->select("*","shop",["name" => $name])[0];
        $shop = new Shop($result["id"],$result["name"],$result["created"]);
        $shop->setStripeKey($stripe);
        $shop->setPaypalKey($paypal);
        return $shop;
    }
    public static function getImage($id){
        return Felta::getInstance()->getSQL()->select("*","shop_item_variant_image",["sid" => $id]);
    }
    public static function deleteImage($url){
        $result = Felta::getInstance()->getSQL()->select("*","shop_item_variant_image",["url" => $url])[0];
        $url = $result["url"];
        Felta::getInstance()->getSQL()->delete("shop_item_variant_image",["id" => $result["id"]]);
        unset($url);
    }
    public static function getItems(){
        return Felta::getInstance()->getSQL()->select("*", "shop_item",[]);
    }
    public static function getItemsByCatagory($catagory){
        return Felta::getInstance()->getSQL()->select("*", "shop_item",["catagory" => $catagory]);
    }
    public static function getVariants($sid){
        return Felta::getInstance()->getSQL()->select("*","shop_item_variant",["sid" => $sid]);
    }
    public function save(){
        if(!$this->sql->exists("shop",["id" => $this->id])){
            $this->sql->insert("shop",[$this->id,$this->name,$this->created->format("Y-m-d H:i:s")]);
        }
    }

    public function addCatagory($catagory){
        if(!$this->sql->exists("shop_catagories",["name" => $catagory]) && $catagory !== ""){
            $this->sql->insert("shop_catagories",[0,$catagory]);
        }
    }
    public function deleteCatagory($catagory){
        if($this->sql->exists("shop_catagories",["id" => $catagory])){
            $this->sql->delete("shop_catagories",["id" => $catagory]);
        }
    }
    public function getCatagories(){
        return Felta::getInstance()->getSQL()->select("*","shop_catagories",[]);
    }

    public function updateShopAddress($street,$number,$zipcode,$city,$country){
        if($this->sql->exists("shop_address",["id" => $this->id])){
            $this->sql->update("street","shop_address",["id" => $this->id],$street);
            $this->sql->update("number","shop_address",["id" => $this->id],$number);
            $this->sql->update("zipcode","shop_address",["id" => $this->id],$zipcode);
            $this->sql->update("city","shop_address",["id" => $this->id],$city);
            $this->sql->update("country","shop_address",["id" => $this->id],$country);
        }else{
            $this->setShopAddress($street,$number,$zipcode,$city,$country);
        }
    }
    public function setShopAddress($street,$number,$zipcode,$city,$country){
        $this->sql->insert("shop_address",[$this->id, $street,$number,$zipcode,$city,$country]);
        return $this;
    }
    public function getShopAddress(){
        return $this->sql->select("*","shop_address",["id" => $this->id])[0];
    }

    public function updateSettings($url,$btw,$exclbtw,$shipping,$freeshipping){
        if($this->sql->exists("shop_settings",["id" => $this->id])){
            $this->sql->update("url","shop_settings",["id" => $this->id],$url);
            $this->sql->update("btw","shop_settings",["id" => $this->id],$btw);
            $this->sql->update("exclbtw","shop_settings",["id" => $this->id],$exclbtw);
            $this->sql->update("shipping","shop_settings",["id" => $this->id],$shipping);
            $this->sql->update("freeshipping","shop_settings",["id" => $this->id],$freeshipping);
        }else{
            $this->setSettings($url,$btw,$exclbtw,$shipping,$freeshipping);
        }
    }
    public function setSettings($url,$btw,$exclbtw,$shipping,$freeshipping){
        $this->sql->insert("shop_settings",[$this->id,$url,$btw,$exclbtw,$shipping,$freeshipping]);
        return $this;
    }
    public function getSettings(){
        return $this->sql->select("*","shop_settings",["id" => $this->id])[0];
    }

    public function updateShipping($amount,$ipp){
        if($this->sql->exists("shop_shipping",["id" => $this->id])){
            $this->sql->update("amount","shop_shipping",["id" => $this->id],$amount);
            $this->sql->update("ipp","shop_shipping",["id" => $this->id],$ipp);
        }else{
            $this->setShipping($amount,$ipp);
        }
    }
    public function setShipping($amount,$ipp){
        $this->sql->insert("shop_shipping",[$this->id,$amount,$ipp]);
        return $this;
    }
    public function getShipping(){
        return $this->sql->select("*","shop_shipping",["id" => $this->id])[0];
    }
    
    public function setPaypalKey($paypal){
        $this->paypal = $paypal;
    }
    public function setStripeKey($stripeKey){
        $this->stripeKey = $stripeKey;
        $this->stripe = Stripe::setAPIKey($this->stripeKey);
        return $this;
    }

    public static function intToDouble($int){
        $behind = substr($int, (strlen($int)  -2),2);
        $front = substr($int, 0,(strlen($int) -2));
        return $front."," .$behind;
    }
    public static function doubleToInt($double){
        return str_replace([",","."], ["",""], $double);
    }
    public static function intToDoubleSeperator($int,$seperator){
        $behind = substr($int, (strlen($int)  -2),2);
        $front = substr($int, 0,(strlen($int) -2));
        return $front.$seperator.$behind;
    }
    public function getId(){
        return $this->id;
    }
    public function getName(){
        return $this->name;
    }

    private function createTables(){
        if(!$this->sql->exists("shop",[])){
            $this->sql->create("shop",[
                "id" => "varchar(255)",
                "name" => "varchar(255)",
                "created" => "DateTime"
            ],"id");
            $this->sql->create("shop_settings",[
                "id" => "varchar(255)",
                "url" => "varchar(255)",
                "btw" => "double",
                "exclbtw" => "boolean",
                "shipping" => "boolean",
                "freeshipping" => "boolean"
            ],"id");
            $this->sql->create("shop_shipping",[
                "id" => "varchar(255)",
                "amount" => "int",
                "ipp" => "int"
            ],"id");
            $this->sql->create("shop_address",[
                "id" => "varchar(255)",
                "street" => "varchar(512)",
                "number" => "varchar(255)",
                "zipcode" => "varchar(255)",
                "city" => "varchar(512)",
                "country" => "varchar(512)"
            ],"id");
            $this->sql->create("shop_cart",[
                "n" => "int auto_increment",
                "id" => "varchar(255)",
                "iid" => "varchar(255)",
                "quantity" => "int"
            ],"n");
            $this->sql->create("shop_catagories",[
                "id" => "int auto_increment",
                "name" => "varchar(512)"
            ],"id");
            $this->sql->create("shop_promotion",[
                "id" => "varchar(255)",
                "code" => "varchar(255)",
                "percentage" => "int",
                "from" => "DateTime",
                "until" => "DateTime",
                "all" => "boolean"
            ],"id");
            $this->sql->create("shop_promotion_items",[
                "id" => "int auto_increment",
                "iid" => "varchar(255)"
            ],"id");
            $this->sql->create("shop_item",[
                "id" => "varchar(255)",
                "name" => "varchar(560)",
                "catagory" => "varchar(512)",
                "description" => "longtext",
                "image" => "int",
                "date" => "DateTime",
                "active" => "boolean"
            ],"id");
            $this->sql->create("shop_item_variant",[
                "id" =>  "varchar(255)",
                "sid" => "varchar(255)",
                "price" => "int",
                "currency" => "varchar(3)",
                "quantity" => "int",
                "variables" => "longtext"
            ],"id");
            $this->sql->create("shop_item_variant_image",[
                "id" => "int auto_increment",
                "sid" => "varchar(255)",
                "url" => "varchar(255)"
            ],"id");
            $this->sql->create("shop_order",[
                "id" => "varchar(255)",
                "customer" => "varchar(255)",
                "orderstatus" => "int",
                "promotion" => "varchar(255)",
                "order" => "DateTime"
            ],"id");
            $this->sql->create("shop_order_item",[
                "id" => "varchar(255)",
                "oid" => "varchar(255)",
                "iid" => "varchar(255)",
                "quantity" => "int"
            ],"id");
            $this->sql->create("shop_transaction",[
                "id" => "varchar(255)",
                "transactionid" => "varchar(255)",
                "order" => "varchar(255)",
                "method" => "varchar(255)",
                "amount" => "int",
                "currency" => "varchar(25)",
                "state" => "int",
                "date" => "DateTime"
            ],"id");
            $this->sql->create("shop_customer",[
                "id" => "varchar(255)",
                "firstname" => "varchar(255)",
                "lastname" => "varchar(255)",
                "email" => "varchar(512)",
                "isBusiness" => "boolean",
                "bName" => "varchar(255)",
                "account" => "boolean"
            ],"id");
            $this->sql->create("shop_customer_address",[
                "id" => "varchar(255)",
                "street" => "varchar(512)",
                "number" => "varchar(255)",
                "zipcode" => "varchar(255)",
                "city" => "varchar(512)",
                "country" => "varchar(512)"
            ],"id");
            $this->sql->create("shop_customer_account",[
                "id" => "varchar(255)",
                "email" => "varchar(255)",
                "password" => "varchar(565)",
                "created" => "DateTime"
            ],"id");
        }
    }
}
?>