<?php
namespace lib\shop\product;

use lib\Felta;
use lib\helpers\UUID;

class ProductVariant{

    private $sql;
    private $active = false;

    private $id;
    private $sid;

    private $name;
    private $price;
    private $currency;
    private $shipsFrom;

    private $images = [];
    private $attributes = [];

    private $quantity;
    private $variables;

    public function __construct(
        $id,
        $sid,
        $name,
        $price,
        $currency,
        $images,
        $attributes,
        $quantity, 
        $variables,
        $active = false
    ){
        $this->sql = Felta::getInstance()->getSQL();
        $this->active = $active;

        $this->id = $id;
        $this->sid = $sid;

        $this->name = $name;
        $this->price = $price;
        $this->currency = $currency;

        $this->images = $images;
        $this->attributes = $attributes;

        $this->quantity = $quantity;
        $this->variables = $variables;
    }

    public static function fromResult($result){
        $images = Felta::getInstance()->getSQL()
            ->select("*","shop_product_variant_image",["sid" => $result["id"]]);
        $attributes = Felta::getInstance()->getSQL()
            ->select("*","shop_product_variant_attribute",["pid" => $result["id"]]);
        $imagesList = [];
        $attributesList = [];
        if($images !== null){
            if(is_array($images)){
                foreach($images as $image){
                    $imagesList[] = $image["url"];
                }
            } else{
                $imagesList[] = $images["url"];
            }
        }

        if ($attributes !== null){
            if(is_array($attributes)){
                foreach($attributes as $attribute){
                    $attributesList[] = Attribute::fromResult($attribute);
                }
            } else{
                $attributesList[] = Attribute::fromResult($attributes);
            }
        }
        
        $productvariant = new ProductVariant(
            $result["id"],
            $result["sid"],
            $result["name"],
            $result["price"],
            $result["currency"],
            $imagesList,
            $attributesList,
            $result["quantity"],
            json_decode($result["variables"])
        );
        return $productvariant;
    }

    public static function create(
        $sid,
        $name,
        $price,
        $currency,
        $images,
        $attributes,
        $quantity, 
        $variables,
        $active = false
    ){
        $id = UUID::generate(15);
        return new ProductVariant(
            $id,
            $sid,
            $name,
            $price,
            $currency,
            $images,
            $attributes,
            $quantity, 
            $variables,
            $active = false
        );
    }

    public static function exists($id){
        return Felta::getInstance()->getSQL()->exists("shop_product_variant",["id" => $id]);
    }

    public static function get($id){
        $result = Felta::getInstance()->getSQL()->select("*","shop_product_variant",["id" => $id])[0];
        return ProductVariant::fromResult($result);
    }

    public function save(){
        $this->sql->insert("shop_product_variant",[
            $this->id,
            $this->sid,
            $this->name,
            $this->price,
            $this->currency,
            $this->quantity,
            json_encode($this->variables)
        ]);

        foreach ($this->images as $image) {
            $this->sql->insert("shop_product_variant_image",[
                0,
                $this->id,
                $image
            ]);
        }

        foreach ($this->attributes as $attribute) {
            $attribute->insert();
        }
    }

    public function delete(){
        $this->sql->delete("shop_product_variant",["id" => $this->id]);
        foreach ($this->images as $image) {
            $this->sql->delete("shop_product_variant_image",["id" => $this->id]);
        }
    }

    public function update(){
        $this->sql->update("name","shop_product_variant",["sid" => $this->sid],$this->name);
        $this->sql->update("price","shop_product_variant",["sid" => $this->sid],$this->price);
        $this->sql->update("currency","shop_product_variant",["sid" => $this->sid],$this->currency);
        $this->sql->update("quantity","shop_product_variant",["sid" => $this->sid],$this->quantity);
        $this->sql->update("variables","shop_product_variant",["sid" => $this->sid],json_encode($this->variables));

        foreach ($this->images as $image) {
            if(!$this->sql->exists("shop_product_variant_image",["url" => $image])){
                $this->sql->insert("shop_product_variant_image",[
                    0,
                    $this->id,
                    $image
                ]);  
            }
        }

        foreach ($this->attributes as $attribute) {
            $attribute->update();
        }
    }

    public function getId(){
        return $this->id;
    }
    public function getSid(){
        return $this->sid;
    }

    public function getName() {
        return $this->name;
    }
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setActive($active){
        $this->active = $active;
        return $this;
    }
    public function getActive(){
        return $this->active;
    }
    
    public function setPrice($price){
        $this->price = $price;
        return $this;
    }
    public function getPrice(){
        return $this->price;
    }

    public function setCurrency($currency){
        $this->currency = $currency;
        return $this;
    }
    public function getCurrency(){
        return $this->currency;
    }

    public function setImages(array $images){
        $this->images = $images;
        return $this;
    }
    public function addImage($image){
        $this->image[] = $images;
        return $this;
    }
    public function getImages(){
        return $this->images;
    }

    public function getQuantity(){
        return $this->quantity;
    }
    public function setQuantity($quantity){
        $this->quantity = $quantity;
        return $this;
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