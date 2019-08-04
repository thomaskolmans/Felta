<?php

use lib\SimpleSQL;

require_once("./autoloader.php");
require_once('./vendor/nytrix/simplesql/autoloader.php');
require_once('./vendor/autoload.php');

$sql = new SimpleSQL();

/**
 * Felta instance
 */
$sql->create("felta",[
    "id" => "int auto_increment",
    "online" => "boolean",
    "date" => "DateTime"
],"id");
$sql->insert("felta",[
    0,
    true,
    (new \DateTime())->format("Y-m-d H:i:s")
]);

/**
 * Settings
 */
$sql->create("settings", [
    "id" => "int auto_increment",
    "name" => "varchar(255)",
    "value" => "longtext"
], "id");

/**
 * Blog
 */
$this->sql->create("blog",[
    "id" => "int auto_increment",
    "title" => "varchar(556)",
    "initiated" => "DateTime"
],"id");
$this->sql->create("blog_article", [
    "id" => "int auto_increment",
    "blog_id" => "int",
    "title" => "varchar(556)",
    "author" => "varchar(255)",
    "content" => "longtext",
    "posted" => "DateTime"
], "id");
$this->sql->create("blog_comment", [
    "id" => "int auto_increment",
    "article_id" => "int",
    "parent_id" => "int",
    "name" => "varchar(255)",
    "comment" => "longtext",
    "posted" => "DateTime"
], "id");
$this->sql->create("blog_like", [
    "id" => "int auto_increment",
    "article_id" => "int",
    "user_id" => "int"
]);
}

/**
 * Statistics
 */

$this->sql->create("visitors_total",[
    "id" => "int auto_increment",
    "ip" => "varchar(65)",
    "date" => "DateTime"
    ],"id");
$this->sql->create("visitors_unique",[
    "id" => "int auto_increment",
    "ip" => "varchar(65)",
    "date" => "DateTime"
    ],"id");
$this->sql->create("visitors_total_location",[
    "id" => "int auto_increment",
    "ip" => "varchar(65)",
    "lat" => "varchar(255)",
    "long" => "varchar(255)",
    "date" => "DateTime"
],"id");
$this->sql->create("visitors_unique_location",[
    "id" => "int auto_increment",
    "ip" => "varchar(65)",
    "lat" => "varchar(255)",
    "long" => "varchar(255)",
    "date" => "DateTime"
],"id");

/**
 *  Shop
 */
$sql->create("shop",[
    "id" => "varchar(255)",
    "name" => "varchar(255)",
    "created" => "DateTime"
],"id");

$sql->create("shop_settings",[
    "id" => "varchar(255)",
    "url" => "varchar(255)",
    "btw" => "double",
    "exclbtw" => "boolean",
    "shipping" => "boolean",
    "freeshipping" => "boolean"
],"id");

$sql->create("shop_shipping",[
    "id" => "varchar(255)",
    "amount" => "int",
    "ipp" => "int"
],"id");

$sql->create("shop_address",[
    "id" => "varchar(255)",
    "street" => "varchar(512)",
    "number" => "varchar(255)",
    "zipcode" => "varchar(255)",
    "city" => "varchar(512)",
    "country" => "varchar(512)"
],"id");

$sql->create("shop_cart",[
    "n" => "int auto_increment",
    "id" => "varchar(255)",
    "iid" => "varchar(255)",
    "quantity" => "int"
],"n");

$sql->create("shop_catagories",[
    "id" => "int auto_increment",
    "name" => "varchar(512)"
],"id");

$sql->create("shop_promotion",[
    "id" => "varchar(255)",
    "code" => "varchar(255)",
    "percentage" => "int",
    "from" => "DateTime",
    "until" => "DateTime",
    "all" => "boolean"
],"id");

$sql->create("shop_promotion_products",[
    "id" => "int auto_increment",
    "product" => "varchar(255)"
],"id");

$sql->create("shop_product",[
    "id" => "varchar(255)",
    "name" => "varchar(560)",
    "catagory" => "varchar(512)",
    "description" => "longtext",
    "image" => "int",
    "date" => "DateTime",
    "active" => "boolean"
],"id");
$sql->create("shop_product_variant",[
    "id" =>  "varchar(255)",
    "sid" => "varchar(255)",
    "price" => "int",
    "currency" => "varchar(3)",
    "quantity" => "int",
    "variables" => "longtext"
],"id");

$sql->create("shop_product_variant_image",[
    "id" => "int auto_increment",
    "sid" => "varchar(255)",
    "url" => "varchar(255)"
],"id");

$sql->create("shop_order",[
    "id" => "varchar(255)",
    "customer" => "varchar(255)",
    "orderstatus" => "int",
    "promotion" => "varchar(255)",
    "order" => "DateTime"
],"id");

$sql->create("shop_order_product",[
    "id" => "varchar(255)",
    "oid" => "varchar(255)",
    "iid" => "varchar(255)",
    "quantity" => "int"
],"id");

$sql->create("shop_transaction",[
    "id" => "varchar(255)",
    "transactionid" => "varchar(255)",
    "order" => "varchar(255)",
    "method" => "varchar(255)",
    "amount" => "int",
    "currency" => "varchar(25)",
    "state" => "int",
    "date" => "DateTime"
],"id");

$sql->create("shop_customer",[
    "id" => "varchar(255)",
    "firstname" => "varchar(255)",
    "lastname" => "varchar(255)",
    "email" => "varchar(512)",
    "isBusiness" => "boolean",
    "bName" => "varchar(255)",
    "account" => "boolean"
],"id");

$sql->create("shop_customer_address",[
    "id" => "varchar(255)",
    "street" => "varchar(512)",
    "number" => "varchar(255)",
    "zipcode" => "varchar(255)",
    "city" => "varchar(512)",
    "country" => "varchar(512)"
],"id");

$sql->create("shop_customer_account",[
    "id" => "varchar(255)",
    "email" => "varchar(255)",
    "password" => "varchar(565)",
    "created" => "DateTime"
],"id");