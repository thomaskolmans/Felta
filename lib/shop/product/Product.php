<?php
namespace lib\shop;

use lib\Felta;
use lib\helpers\UUID;

class Product{

    private $sql;

    private $id;
    private $name;
    private $slug;
    private $catagory;
    private $description;
    private $image;
    private $date;

    private $active;

    private $itemVariants = [];

    public function __construct($id,$name,$slug,$catagory,$description,$image,$date,$active = false){
        $this->sql = Felta::getInstance()->getSQL();

        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->catagory = $catagory;
        $this->description = $description;
        $this->image = $image;
        $this->date = $date;
        $this->active = $active;
    }

    public static function fromResult($result){
        $variants = Felta::getInstance()->getSQL()->select("id","shop_product_variant",["sid" => $result["id"]]);
        $product = new Product(
            $result["id"],
            $result["name"],
            $result["slug"],
            $result["catagory"],
            $result["description"],
            $result["image"],
            $result["date"],
            $result["active"]
        );
        
        if($variants !== null){
            if(is_array($variants)){
                foreach($variants as $variant){
                    $product->addVariant(ProductVariant::get($variant));
                }
            }else{
                $product->addVariant(ProductVariant::get($variants));
            }
        }
        return $product;
    }

    public static function create($name,$catagory,$description,$image,$active = false){
        $id = UUID::generate(6);
        $date = new \DateTime();
        return new Product($id,$name,$catagory,$description,$image,$date,$active = false);
    }

    public static function exists($id){
        return Felta::getInstance()->getSQL()->exists("shop_product",["id" => $id]);
    }

    public static function get($id){
        $result = Felta::getInstance()->getSQL()->select("*","shop_product",["id" => $id])[0];
        return Product::fromResult($result);
    }
    
    public static function getSolo($id){
        $result = Felta::getInstance()->getSQL()->select("*","shop_product",["id" => $id])[0];
        return Product::fromResult($result);
    }
    
    public function save(){
        $this->sql->insert("shop_product",[
            $this->id,
            $this->name,
            $this->slug,
            $this->catagory,
            $this->description,
            $this->image,
            $this->date->format("Y-m-d H:i:s"),
            $this->active
        ]);
        foreach($this->itemVariants as $variant){
            $variant->save();
        }
    }

    public function update(){
        $this->sql->update("name","shop_product",["id" => $this->id],$this->name);
        $this->sql->update("slug","shop_product",["id" => $this->id],$this->slug);

        $this->sql->update("catagory","shop_product",["id" => $this->id],$this->catagory);
        $this->sql->update("description","shop_product",["id" => $this->id],$this->description);
        $this->sql->update("image","shop_product",["id" => $this->id],$this->image);
        $this->sql->update("date","shop_product",["id" => $this->id],$this->date);
        $this->sql->update("active","shop_product",["id" => $this->id],$this->active);

        foreach($this->itemVariants as $variant){
            $variant->update();
        }
    }

    public function delete(){
        $this->sql->delete("shop_product",["id" => $this->id]);
        foreach($this->itemVariants as $variant){
            $variant->delete();
        }
    }
    
    public function getId(){
        return $this->id;
    }
    public function setId($id){
        $this->id = $id;
        return $this;
    }
    public function getName(){
        return $this->name;
    }
    public function setName($name){
        $this->name = $name;
        return $this;
    }
    public function getSlug(){
        return $this->slug;
    }
    public function setSlug($slug){
        $this->slug = $slug;
        return $this;
    }
    public function getcatagory(){
        return $this->catagory;
    }
    public function setCatagory($catagory){
        $this->catagory = $catagory;
        return $this;
    }
    public function getDescription(){
        return $this->description;
    }
    public function setDescription($description){
        $this->description = $description;
        return $this;
    }
    public function getActive(){
        return $this->active;
    }
    public function setActive($active){
        $this->active = $active;
        return $this;
    }
    public function getVariants(){
        return $this->itemVariants;
    }
    public function addVariant($variant){
        $this->itemVariants[] = $variant;
        return $this;
    }
    public function setVariants($variants){
        $this->itemVariants = $variants;
        return $this;
    }

}