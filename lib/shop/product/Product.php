<?php
namespace lib\shop\product;

use lib\Felta;
use lib\helpers\UUID;

class Product{

    private $sql;

    private $id;
    private $name;
    private $slug;
    private $category;
    private $shortDescription;
    private $description;
    private $image;
    private $date;

    private $active;

    private $variants = [];

    public function __construct($id, $name, $slug, $category, $shortDescription, $description, $image, $date, $active = false){
        $this->sql = Felta::getInstance()->getSQL();

        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->category = $category;
        $this->shortDescription = $shortDescription;
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
            $result["category"],
            $result["short_description"],
            $result["description"],
            $result["image"],
            $result["date"],
            $result["active"]
        );
        if($variants !== null){
            if(is_array($variants)){
                foreach($variants as $variant){
                    $product->addVariant(ProductVariant::get($variant["id"]));
                }
            }else{
                $product->addVariant(ProductVariant::get($variants));
            }
        }
        return $product;
    }

    public static function create($name, $slug, $category, $shorDescription, $description, $image, $active = false) {
        $id = UUID::generate(6);
        $date = new \DateTime();
        return new Product($id,$name,$slug,$category,$shorDescription,$description,$image,$date,$active = false);
    }

    public static function exists($id){
        return Felta::getInstance()->getSQL()->exists("shop_product",["id" => $id]);
    }

    public static function get($id){
        $result = Felta::getInstance()->getSQL()->select("*","shop_product",["id" => $id])[0];
        if($result == null) return null;
        return Product::fromResult($result);
    }

    public static function getFromSlug($slug) {
        $result = Felta::getInstance()->getSQL()->select("*","shop_product",["slug" => $slug])[0];
        if($result == null) return null;
        return Product::fromResult($result);
    }
    
    public static function getSolo($id){
        $result = Felta::getInstance()->getSQL()->select("*","shop_product",["id" => $id])[0];
        if($result == null) return null;
        return Product::fromResult($result);
    }

    public static function all($from = 0, $amount = 20) {
        $results = Felta::getInstance()->getSQL()->select("*","shop_product",[]);
        $products = [];
        foreach($results as $result) {
            $products[] = Product::fromResult($result);
        }
        return $products;
    }

    public static function allWithCategory($category, $from = 0, $amount = 20) {
        $results = Felta::getInstance()->getSQL()->query()->select()->from("shop_product")->where("category", $category)->execute();
        $products = [];
        foreach($results as $result) {
            $products[] = Product::fromResult($result);
        }
        return $products;
    }
    
    public function getLowestPrice() {
        $lowestPrice = null;
        foreach($this->variants as $variant) {
            if ($lowestPrice === null || $variant->getPrice() < $lowestPrice) {
                $lowestPrice = $variant->getPrice();
            }
        }
        return $lowestPrice;
    }

    public function getHighestPrice() {
        $highestPrice = null;
        foreach($this->variants as $variant) {
            if ($highestPrice === null || $variant->getPrice() > $highestPrice) {
                $highestPrice = $variant->getPrice();
            }
        }
        return $highestPrice;
    }

    public function getAveragePrice() {
        $prices = [];
        foreach($this->variants as $variant) {
            $prices[] = $variant->getPrice();
        }
        return array_sum($prices) / count($prices);
    }

    public function save(){
        $this->sql->insert("shop_product",[
            $this->id,
            $this->name,
            $this->slug,
            $this->category,
            $this->shortDescription,
            $this->description,
            $this->image,
            $this->date->format("Y-m-d H:i:s"),
            $this->active
        ]);
        foreach($this->variants as $variant){
            $variant->save();
        }
    }

    public function update(){
        $this->sql->update("name","shop_product",["id" => $this->id],$this->name);
        $this->sql->update("slug","shop_product",["id" => $this->id],$this->slug);

        $this->sql->update("category","shop_product",["id" => $this->id],$this->category);
        $this->sql->update("short_description","shop_product",["id" => $this->id],$this->shortDescription);
        $this->sql->update("description","shop_product",["id" => $this->id],$this->description);
        $this->sql->update("image","shop_product",["id" => $this->id],$this->image);
        $this->sql->update("date","shop_product",["id" => $this->id],$this->date);
        $this->sql->update("active","shop_product",["id" => $this->id],$this->active);

        $this->sql->delete("shop_product_variant", ["sid" => $this->id]);
        foreach($this->variants as $variant){
            $variant->update();
        }
    }

    public function delete(){
        $this->sql->delete("shop_product",["id" => $this->id]);
        foreach($this->variants as $variant){
            $variant->delete();
        }
    }

    public function expose(){
        $exposed = get_object_vars($this);
		unset($exposed["sql"]);
	    return $exposed;
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
    public function getCategory(){
        return $this->category;
    }
    public function setCategory($category){
        $this->category = $category;
        return $this;
    }
    public function getShortDescription(){
        return $this->shortDescription;
    }
    public function setShortDescription($shortDescription){
        $this->shortDescription = $shortDescription;
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
        return $this->variants;
    }
    public function addVariant($variant){
        $this->variants[] = $variant;
        return $this;
    }
    public function setVariants($variants){
        $this->variants = $variants;
        return $this;
    }

}