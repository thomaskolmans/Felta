<?php
namespace lib\shop;

use \DateTime;
use \lib\Felta;
use \Stripe\Stripe;
use \lib\helpers\UUID;

class Shop {
    
    private $sql;

    public $id;
    public $name;
    public $created;

    private $paypalKey;
    private $stripeKey;
    private $stripePublicKey;
    private $mollieKey;

    private $paypal;
    private $stripe;
    private $mollie;

    public static $instance;

    public function __construct($id,$name,$created){
        $this->sql = Felta::getInstance()->getSQL();

        $this->id = $id;
        $this->name = $name;
        $this->created = $created;

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

    public static function create($name){
        $sql = Felta::getInstance()->getSQL();
        if($sql->exists("shop",[])){
            if($sql->exists("shop",["name" => $name])){
                return Shop::get($name);
            } else {
                return Shop::generate($name);
            }
        }else{
            return Shop::generate($name);
        }
    }

    public static function generate($name){
        $id = UUID::generate(10);
        $shop = new Shop($id,$name,new \DateTime());
        $shop->save();
        return $shop;
    }

    public static function get($name){
        $result = Felta::getInstance()->getSQL()->select("*","shop",["name" => $name])[0];
        $shop = new Shop($result["id"],$result["name"],$result["created"]);
        return $shop;
    }

    public static function getImage($id){
        return Felta::getInstance()->getSQL()->select("*","shop_product_variant_image",["sid" => $id]);
    }

    public static function deleteImage($url){
        $result = Felta::getInstance()->getSQL()->select("*","shop_product_variant_image",["url" => $url])[0];
        $url = $result["url"];
        Felta::getInstance()->getSQL()->delete("shop_product_variant_image",["id" => $result["id"]]);
        unset($url);
    }

    public static function getItems($from = 0, $to = 0){
        return Felta::getInstance()->getSQL()->query()
            ->select()->from("shop_product")->orderBy("date")->desc()->limit($from, $to)->execute();
    }

    public static function getItemsByCatagory($catagory){
        return Felta::getInstance()->getSQL()->select("*", "shop_product",["catagory" => $catagory]);
    }

    public static function getVariants($sid){
        return Felta::getInstance()->getSQL()->select("*","shop_product_variant",["sid" => $sid]);
    }

    public function save(){
        if(!$this->sql->exists("shop",["id" => $this->id])){
            $this->sql->insert("shop",[$this->id,$this->name,$this->created->format("Y-m-d H:i:s")]);
        }
    }

    public function addCategory($category){
        if(!$this->sql->exists("shop_categories",["name" => $category]) && $category !== ""){
            $this->sql->insert("shop_categories",[0,$category]);
        }
    }
    public function deleteCategory($category){
        if($this->sql->exists("shop_categories",["id" => $category])){
            $this->sql->delete("shop_categories",["id" => $category]);
        }
    }

    public static function getCategories(){
        return Felta::getInstance()->getSQL()->select("*","shop_categories",[]);
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
    
    public static function intToDouble($int){
        $behind = substr($int, (strlen($int)  -2),2);
        $front = substr($int, 0,(strlen($int) -2));
        return doubleval($front."." .$behind);
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

    public function setPaypalKey($paypal){
        $this->paypal = $paypal;
    }

    public function setStripeKey($stripeKey){
        $this->stripeKey = $stripeKey;
        $this->stripe = Stripe::setAPIKey($this->stripeKey);
        self::$instance = $this;
        return $this;
    }

    public function setMollieKey($mollieKey){
        $this->mollieKey = $mollieKey;
        $this->mollie = new \Mollie\Api\MollieApiClient();
        $this->mollie->setApiKey($mollieKey);
        self::$instance = $this;
        return $this;
    }

    public function getStripePublicKey(){
        return $this->stripePublicKey;
    }

	public function getPaypal(){
		return $this->paypal;
	}
	
	
	public function setPaypal($paypal){
		$this->paypal = $paypal;
		return $this;
	}
	

	public function getStripe(){
		return $this->stripe;
	}
	
	
	public function setStripe($stripe){
		$this->stripe = $stripe;
		return $this;
	}
	

	public function getMollie(){
		return $this->mollie;
	}
	
	
	public function setMollie($mollie){
		$this->mollie = $mollie;
		return $this;
	}
	
}
?>