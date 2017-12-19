<?php

namespace lib\Shop;

class ShopItem{

    public $active = false;

    public $price;
    public $currency;

    public $name;
    public $description;
    public $images = [];

    public $quantity;
    public $colors = [];
    public $sizes = [];

    public function __construct(){
        
    }
}

?>