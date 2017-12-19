<?php
namespace lib\Shop;

class Shop{
    
    public $id;
    public $items = [];
    public $catagories = [];

    public function __construct(){
        
    }
    public function getItems(){
        $this->items = $sql->select("*","shop_items",[]);
    }
    public function searchItems($query){
        
    }
    public function addItem(ShopItem $item){
        $this->items[] = $item;
    }
}
?>