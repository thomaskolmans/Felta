<?php
namespace lib\Shop;

abstract class OrderStatus{

    const ACTIVE = 0;
    const PAID = 1;
    const SHIPPING = 3;
    const DELIVERED = 4;
    
}