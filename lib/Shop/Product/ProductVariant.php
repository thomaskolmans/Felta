<?php
namespace lib\Shop;

use lib\Felta;
use lib\Helpers\UUID;

class ShopItemVariant{

    private $sql;
    private $active = false;

    private $id;
    private $sid;

    private $price;
    private $currency;

    private $images = [];

    private $quantity;
    private $variables;

    public function __construct($id,$sid,$price,$currency,$images,$quantity,$variables,$active = false){
        $this->sql = Felta::getInstance()->getSQL();
        $this->active = $active;

        $this->id = $id;
        $this->sid = $sid;

        $this->price = $price;
        $this->currency = $currency;

        $this->images = $images;
        $this->quantity = $quantity;
        $this->variables = $variables;
    }

    public function save(){
        $this->sql->insert("shop_item_variant",[
            $this->id,
            $this->sid,
            $this->price,
            $this->currency,
            $this->quantity,
            json_encode($this->variables)
        ]);
        foreach ($this->images as $image) {
            $this->sql->insert("shop_item_variant_image",[
                0,
                $this->id,
                $image
            ]);
        }
    }

    public function delete(){
        $this->sql->delete("shop_item_variant",["id" => $this->id]);
        foreach ($this->images as $image) {
            $this->sql->delete("shop_item_variant_image",["id" => $this->id]);
        }
    }

    public function update(){
        $this->sql->update("price","shop_item_variant",["sid" => $this->sid],$this->price);
        $this->sql->update("currency","shop_item_variant",["sid" => $this->sid],$this->currency);
        $this->sql->update("quantity","shop_item_variant",["sid" => $this->sid],$this->quantity);
        $this->sql->update("variables","shop_item_variant",["sid" => $this->sid],json_encode($this->variables));

        foreach ($this->images as $image) {
            if(!$this->sql->exists("shop_item_variant_image",["url" => $image])){
                $this->sql->insert("shop_item_variant_image",[
                    0,
                    $this->id,
                    $image
                ]);  
            }
        }
    }

    public static function create($sid,$price,$currency,$images,$quantity,$variables,$active = true){
        $id = UUID::generate(15);
        return new ShopItemVariant($id,$sid,$price,$currency,$images,$quantity,$variables,$active = false);
    }
    public static function exists($id){
        return Felta::getInstance()->getSQL()->exists("shop_item_variant",["id" => $id]);
    }

    public static function get($id){
        $sql = Felta::getInstance()->getSQL();
        $r = $sql->select("*","shop_item_variant",["id" => $id])[0];
        $images = $sql->select("*","shop_item_variant_image",["sid" => $id]);
        $imageslist = [];

        if($images !== null){
            if(is_array($images)){
                foreach($images as $image){
                    $imageslist[] = $image["url"];
                }
            }else{
                $imageslist[] = $images["url"];
            }
        }
        $shopitemvariant = new ShopItemVariant(
            $id,
            $r["sid"],
            $r["price"],
            $r["currency"],
            $imageslist,
            $r["quantity"],
            json_decode($r["variables"])
        );
        return $shopitemvariant;
    }
    public function getId(){
        return $this->id;
    }
    public function getSid(){
        return $this->sid;
    }
    public function setActive($active){
        $this->active = $active;
    }
    public function getActive(){
        return $this->active;
    }
    public function setPrice($price){
        $this->price = $price;
    }
    public function getPrice(){
        return $this->price;
    }
    public function setCurrency($currency){
        $this->currency = $currency;
    }
    public function getCurrency(){
        return $this->currency;
    }
    public function setImages(array $images){
        $this->images = $images;
    }
    public function addImage($image){
        $this->image[] = $images;
    }
    public function getImages(){
        return $this->images;
    }
    public function getQuantity(){
        return $this->quantity;
    }
    public function setQuantity($quantity){
        $this->quantity = $quantity;
    }
    public function getVariables(){
        return $this->variables;
    }
    public function setVariables($variables){
        $this->variables = $variables;
        return $this;
    }
}

?>