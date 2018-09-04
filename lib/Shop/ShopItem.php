<?php
namespace lib\Shop;

use lib\Felta;
use lib\Helpers\UUID;

namespace lib\Shop;

class ShopItem{

    private $sql;

    private $id;
    private $name;
    private $catagory;
    private $description;
    private $image;
    private $date;
    private $active;

    private $itemVariants = [];

    public function __construct($id,$name,$catagory,$description,$image,$date,$active = false){
        $this->sql = Felta::getInstance()->getSQL();

        $this->id = $id;
        $this->name = $name;
        $this->catagory = $catagory;
        $this->description = $description;
        $this->image = $image;
        $this->date = $date;
        $this->active = $active;
    }

    public function save(){
        $this->sql->insert("shop_item",[
            $this->id,
            $this->name,
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
        
        $this->sql->update("name","shop_item",["id" => $this->id],$this->name);
        $this->sql->update("catagory","shop_item",["id" => $this->id],$this->catagory);
        $this->sql->update("description","shop_item",["id" => $this->id],$this->description);
        $this->sql->update("image","shop_item",["id" => $this->id],$this->image);
        $this->sql->update("date","shop_item",["id" => $this->id],$this->date);
        $this->sql->update("active","shop_item",["id" => $this->id],$this->active);

        foreach($this->itemVariants as $variant){
            $variant->update();
        }
    }
    public function delete(){
        $this->sql->delete("shop_item",["id" => $this->id]);
        foreach($this->itemVariants as $variant){
            $variant->delete();
        }
    }
    public static function create($name,$catagory,$description,$image,$active = false){
        $id = UUID::generate(6);
        $date = new \DateTime();
        return new ShopItem($id,$name,$catagory,$description,$image,$date,$active = false);
    }
    public static function exists($id){
        return Felta::getInstance()->getSQL()->exists("shop_item",["id" => $id]);
    }
    public static function get($id){
        $result = Felta::getInstance()->getSQL()->select("*","shop_item",["id" => $id])[0];
        $variants = Felta::getInstance()->getSQL()->select("id","shop_item_variant",["sid" => $id]);
        $shopitem = new ShopItem(
            $id,
            $result["name"],
            $result["catagory"],
            $result["description"],
            $result["image"],
            $result["date"],
            $result["active"]
        );
        
        if($variants !== null){
            if(is_array($variants)){
                foreach($variants as $variant){
                    $shopitem->addVariant(ShopItemVariant::get($variant));
                }
            }else{
                $shopitem->addVariant(ShopItemVariant::get($variants));
            }
        }
        return $shopitem;
    }
    public static function getSolo($id){
        $result = Felta::getInstance()->getSQL()->select("*","shop_item",["id" => $id])[0];
        $shopitem = new ShopItem(
            $id,
            $result["name"],
            $result["catagory"],
            $result["description"],
            $result["image"],
            $result["date"],
            $result["active"]
        );
        return $shopitem;
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