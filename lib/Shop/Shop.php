<?php

class Shop{
    
    public $id;
    public $items = [];

    public function getItems(){
        $this->items = $sql->select("*","shop_items",[]);
    }
    public function addItem(ShopItem $item){
        $this->items[] = $item;
    }
}
?>