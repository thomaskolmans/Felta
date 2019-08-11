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

    private $sizeWidth;
    private $sizeHeight;
    private $sizeDepth;

    private $weight;

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
        $sizeWidth,
        $sizeHeight,
        $sizeDepth,
        $weight,
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

        $this->sizeWidth = $sizeWidth;
        $this->sizeHeight = $sizeHeight;
        $this->sizeDepth = $sizeDepth;

        $this->weight = $weight;
        
        $this->images = $images;
        $this->attributes = $attributes;

        $this->quantity = $quantity;
        $this->variables = $variables;
    }

    public static function fromResult($result){
        $images = Felta::getInstance()->getSQL()
            ->select("*","shop_product_variant_image", ["sid" => $result["id"]]);
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
            $result["size_width"],
            $result["size_height"],
            $result["size_depth"],
            $result["weight"],
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
        $sizeWidth,
        $sizeHeight,
        $sizeDepth,
        $weight,
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
            $sizeWidth,
            $sizeHeight,
            $sizeDepth,
            $weight,
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
        return ProductVariant::fromResult(Felta::getInstance()->getSQL()->select("*","shop_product_variant",["id" => $id])[0]);
    }

    public function save(){
        $this->sql->insert("shop_product_variant",[
            $this->id,
            $this->sid,
            $this->name,
            $this->price,
            $this->currency,
            $this->sizeWidth,
            $this->sizeHeight,
            $this->sizeDepth,
            $this->weight,
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
            var_dump($attribute);
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
        $this->sql->delete("shop_product_variant_image", ["sid" => $this->id]);
        $this->sql->delete("shop_product_variant_attribute", ["pid" => $this->id]);

       $this->save();
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

    public function getAttributes(){
        return $this->attributes;
    }
    public function setAttributes($attributes){
        $this->attributes = $attributes;
        return $this;
    }

    public function getSizeWidth(){
        return $this->sizeWidth;
    }

    public function setSizeWidth($sizeWidth){
        $this->sizeWidth = $sizeWidth;
        return $this;
    }

    public function getSizeHeight(){
        return $this->sizeHeight;
    }

    public function setSizeHeight($sizeHeight){
        $this->sizeHeight = $sizeHeight;
        return $this;
    }

    public function getSizeDepth(){
        return $this->sizeDepth;
    }

    public function setSizeDepth($sizeDepth){
        $this->sizeDepth = $sizeDepth;
        return $this;
    }

    public function getWeight(){
        return $this->weight;
    }

    public function setWeight($weight){
        $this->weight = $weight;
        return $this;
    }
}

?>