<?php
namespace lib\Shop;

use lib\Felta;
use lib\Helpers\UUID;

class Shoppingcart{

    private $sql;

    private $id;
    private $items = [];

    public function __construct($id){
        $this->sql = Felta::getInstance()->getSQL();
        $this->id = $id;
    }


    public static function create(){
        return new Shoppingcart(UUID::generate(20));
    }

    public static function exists($id){
        return Felta::getInstance()->getSQL()->exists("shop_cart",["id" => $id]);
    }

    public function save(){
        foreach($this->items as $item => $quantity){
            if($this->sql->exists("shop_cart",["iid" => $item, "id" => $this->id])){
                $this->updatesql($item,$quantity);
            }else{
                $this->sql->insert("shop_cart",[
                    0,
                    $this->id,
                    $item,
                    $quantity
                ]);     
            }
        }   
    }

    public function updatesql($item,$quantity){
        $this->sql->update("quantity","shop_cart",["id" => $this->id,"iid" => $item],$quantity);
    }

    public function pull(){
        $results = $this->sql->select("*","shop_cart",["id" => $this->id]);
        if($results !== null){
            foreach($results as $result){
                $this->set($result["iid"],$result["quantity"]);
            }
        }
        return $this;
    }

    public function set($item,$quantity){
        if(ShopItemVariant::exists($item) && is_numeric($quantity)){
            $this->items[$item] = $quantity;
            $this->save();
        }
    }
    public function add($item,$quantity){
        if(ShopItemVariant::exists($item) && is_numeric($quantity)){
            $this->items[$item] = $quantity;
            $this->save();
        }
    }
    public function update($item,$quantity){
        if(ShopItemVariant::exists($item) && is_numeric($quantity)){
            $this->items[$item] = $quantity;
            $this->updatesql($item,$quantity);
        }

    }
    public function delete($item){
        unset($this->items[$item]);
        $this->sql->delete("shop_cart",["id" => $this->id, "iid" => $item]);
    }

    public function destroy(){
        $this->sql->delete("shop_cart",["id" => $this->id]);
    }

    public function getId(){
        return $this->id;
    }
    public function getItems(){
        return $this->items;
    }

    public function getSubTotal(){
        $amount = 0;
        $settings = Shop::getInstance()->getSettings();
        if(boolval($settings["exclbtw"])){
            foreach($this->items as $item => $quantity){
                $itemv = ShopItemVariant::get($item);
                $amount += intval($itemv->getPrice()) * $quantity;
            }
            if(boolval($settings["shipping"]) && !boolval($settings["freeshipping"])){
                $amount += $this->getShippingCost();
            }
        } else {
            foreach($this->items as $item => $quantity){
                $itemv = ShopItemVariant::get($item);
                $amount += intval($itemv->getPrice()) * $quantity;
            }
            if(boolval($settings["shipping"]) && !boolval($settings["freeshipping"])){
                $amount += $this->getShippingCost();
            }
            $amount -= $this->getBtw($amount, true); 
        }

        return $amount;
    }

    public function getTotalAmount(){
        $amount = 0;
        $settings = Shop::getInstance()->getSettings();
        foreach($this->items as $item => $quantity){
            $itemv = ShopItemVariant::get($item);
            $amount += intval($itemv->getPrice()) * $quantity;
        }
        if(boolval($settings["shipping"]) && !boolval($settings["freeshipping"])){
            $amount += $this->getShippingCost();
        }
        if(boolval($settings["exclbtw"])){
            $amount += $this->getBtw($amount, true);
        }
        return $amount;
    }

    public function getBtw($amount,$excl = false){
        $exclBtw = boolval(Shop::getInstance()->getSettings()["exclbtw"]);
        if($excl || !$exclBtw){
            return $amount - Shop::doubleToInt(round(Shop::intToDouble($amount) / ((Shop::getInstance()->getSettings()["btw"] / 100) + 1), 2));
        }else{
            return Shop::doubleToInt(round(Shop::intToDouble($amount) * (Shop::getInstance()->getSettings()["btw"] / 100),2));
        }
    }
    
    public function getShippingCost(){
        $items = count($this->items);
        $settings = Shop::getInstance()->getShipping();
        $price = $settings["amount"];
        $ipp = $settings["ipp"];

        $amount = $price;
        $counter = 0;
        foreach($this->items as $item => $quantity){
            $counter += $quantity;
            if($counter > $ipp){
                $amount += $price;
                $counter -= $ipp;
            }
        }
        return $amount;
    }
}

?>