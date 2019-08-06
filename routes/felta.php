<?php
use lib\Cleverload;
use lib\Felta;

use lib\Controllers\FeltaController;
use lib\Controllers\ShopController;
use lib\Controllers\UserController;
use lib\Controllers\PostController;

use lib\Routing\Route;
use lib\Helpers\Input;

$felta = Felta::getInstance();

Route::group(["namespace" => "/felta"], function() use ($felta){
    if($felta->user->hasSession()){
        
        /* Views */
        Route::any("/","felta/dashboard.tpl")->primary();
        Route::get("/editor","felta/editor.tpl");
        Route::get("/social","felta/social.tpl");
        Route::get("/settings","felta/settings.tpl");
        Route::get("/agenda","felta/agenda.tpl");
        Route::get("/agenda/{id}/update","felta/agenda.tpl");
        Route::get("/news","felta/news.tpl");
        Route::get("/news/{id}/update","felta/news.tpl");
        Route::get("/blog","felta/blog_main.tpl");

        Route::post("/settings",function(){ UserController::UPDATE_SETTINGS(); });
        Route::post("/settings/delete/user",function(){ UserController::DELETE_USER(); });

        Route::get("/logout",function(){ UserController::LOGOUT(); });
        Route::post("/status",function(){ FeltaController::SET_STATUS(); });

        /**
         * News
         */
        Route::post("/news",function() { PostController::ADD_NEWS(); });
        Route::post("/news/update",function() { PostController::UPDATE_NEWS(); });
        Route::get("/news/delete/{id}",function($id){ PostController::DELETE_NEWS($id); });

        /**
         * Agenda
         */
        Route::post("/agenda",function(){ Postcontroller::ADD_AGENDA(); });
        Route::post("/agenda/update",function(){ PostController::UPDATE_AGENDA(); });
        Route::get("/agenda/delete/{id}",function($id){ PostController::DELETE_AGENDA($id); });

        /**
         * Blog
         */
        Route::post("/blog",function(){ Postcontroller::ADD_AGENDA(); });
        Route::post("/blog/update",function(){ PostController::UPDATE_AGENDA(); });
        Route::get("/blog/delete/{id}",function($id){ PostController::DELETE_AGENDA($id); });

        /**
         * Audio
         */
        Route::post("/audio", function(){ Postcontroller::ADD_AGENDA(); });
        Route::post("/audio/update", function(){ PostController::UPDATE_AGENDA(); });
        Route::get("/audio/delete/{id}", function($id){ PostController::DELETE_AGENDA($id); });

        /**
         * Newsletter
         */
        Route::get("/newsletter/{id}", function(){ PostController::ADD_AGENDA(); });
        Route::post("/newsletter", function(){ Postcontroller::ADD_AGENDA(); });
        Route::post("/newsletter/send", function(){ PostController::UPDATE_AGENDA(); });
        Route::post("/newsletter/subscribe", function(){ PostController::UPDATE_AGENDA(); });
        Route::post("/newsletter/unsubscribe", function(){ PostController::UPDATE_AGENDA(); });

        /**
         * Edit
         */
        Route::delete("/language/{language}",function($language){ PostController::DELETE_LANGUAGE($language); });
        Route::get("/edit/id/{id}/lang/{language}",function($id,$language){ PostController::GET_TEXT($id,$language); });
        Route::get("/edit/history/{id}", function($id){ /*  Has to implemented */ });
        Route::post("/edit",function(){ PostController::SET_TEXT(); });
        Route::post("/edit/image",function(){ PostController::SET_IMAGE(); });

        /**
         * Statistics
         */
        Route::group(["namespace" => "/statistics"],function(){
            Route::get("/","felta/statistics.tpl");
            Route::get("/unique",function() { echo json_encode(Felta::getInstance()->getSQL()->execute("select count(*) from visitors_unique")); });
            Route::get("/total",function() { echo json_encode(Felta::getInstance()->getSQL()->execute("select count(*) from visitors_total")); });
            Route::get("/unique/today",function(){ echo json_encode(Felta::getInstance()->getSQL()->execute("SELECT date,count(*) FROM visitors_unique WHERE date >= NOW() - INTERVAL 1 DAY GROUP BY hour( date ) , day( date ) ORDER BY date")); });
            Route::get("/total/today",function(){ echo json_encode(Felta::getInstance()->getSQL()->execute("SELECT date,count(*) FROM visitors_total WHERE date >= NOW() - INTERVAL 1 DAY GROUP BY hour( date ) , day( date ) ORDER BY date")); });  
        });

        /**
         * Shop
         */
        Route::group(["namespace" => "/shop"],function(){
            Route::get("/","felta/shop/dashboard.tpl");
            Route::get(["/products", "/products/{from}/{until}"], "felta/shop/products.tpl");
            Route::get("/categories", "felta/shop/categories.tpl");
            Route::get("/orders/{from}/{until}","felta/shop/orders.tpl");

            Route::get("/items", "felta/shop/items.tpl");

            Route::get("/add/item","felta/shop/create.tpl");
            Route::get("/update/item/{id}","felta/shop/update.tpl");

            Route::get("/settings","felta/shop/settings.tpl");
            
            Route::get("/transactions/{from}/{until}","felta/shop/transactions.tpl");
            Route::get("/transactions/week", function(){ ShopController::WEEK_TRANSACTIONS(); });
            Route::get("/transaction/{tid}","felta/shop/transaction.tpl");

            Route::post("/add/item",function(){ ShopController::ADD_ITEM(); });
            Route::post("/update/item",function(){ ShopController::UPDATE_ITEM(); });
            Route::get("/delete/item/{id}",function($id){ ShopController::DELETE_ITEM($id); });

            /**
             * Image
             */
            Route::post("/upload/image",function(){ ShopController::UPLOAD_IMAGE(); });
            Route::post("/delete/image",function(){ ShopController::DELETE_IMAGE(); });

           /** 
             * Settings
             */
            Route::post("/address",function(){ ShopController::UPDATE_ADDRESS(); });
            Route::post("/settings",function(){ ShopController::UPDATE_SETTINGS(); });
            Route::post("/shipping",function(){ ShopController::UPDATE_SHIPPING(); });

            /**
             * Category
             */
            Route::post("/add/catagory",function(){ ShopController::ADD_CATEGORY(); });
            Route::post("/delete/catagory",function(){ ShopController::DELETE_CATEGORY(); });

            /**
             * Promotion
             */
            Route::post("/delete/promotion",function(){ });
            Route::post("/add/promotion",function(){ });

        });

    } else {

        /**
         * Views
         */
        Route::get("/","felta/login.tpl")->primary();
        Route::any("/forgot","felta/reset.tpl");
        Route::get("/forgot/code/{code}","felta/reset.tpl");

        /**
         * Login, register and recover
         */
        Route::get("/user/verify/{key}",function($key){ UserController::VERIFY_EMAIL($key); });

        Route::post("/login",function(){ UserController::LOGIN(); });
        Route::post("/register", function(){ UserController::REGISTER(); });
    }

    Route::group(["namespace" => "/shop"],function(){
        /**
         * Charge
         */
        Route::post("/charge",function(){ ShopController::CHARGE(); });
        Route::post("/charge/source",function(){ ShopController::CHARGE_SOURCE(); });
        Route::post("/webhook",function(){ ShopController::WEBHOOK(); });

        /**
         * Transaction
         */
        Route::post("/transaction",function(){ ShopController::TRANSACTION(); });

        /**
         * Order
         */
        Route::post("/create/order",function(){ ShopController::CREATE_TRANSACTION(); });
        Route::post("/create/source/ideal", function(){ ShopController::CREATE_SOURCE_IDEAL(); });

        Route::get("/catagories",function(){ });

        /**
         *  Shoppingcart
         */
        Route::get("/create/shoppingcart", function(){ ShopController::CREATE_SHOPPINGCART(); });
        Route::post("/add/shoppingcart", function(){ ShopController::ADD_ITEM_SHOPPINGCART(); });
        Route::post("/update/shoppingcart", function(){ ShopController::UPDATE_ITEM_SHOPPINGCART(); });
        Route::post("/delete/shoppingcart", function(){ ShopController::DELETE_ITEM_SHOPPINGCART(); });

        /**
         * Wishlist
         */
        Route::get("/create/wishlist", function(){ ShopController::CREATE_WISHLIST(); });
        Route::post("/add/wishlist", function(){ ShopController::ADD_ITEM_WISHLIST(); });
        Route::post("/update/wishlist", function(){ ShopController::UPDATE_ITEM_WISHLIST(); });
        Route::post("/delete/wishlist", function(){ ShopController::DELETE_ITEM_WISHLIST(); });
        
        /**
         *  Default UI for website flow 
         */
        Route::get(["/pay/{oid}","/checkout/{oid}"],"felta/shop/pay.tpl");
        Route::get("/thankyou/{oid}","felta/shop/thankyou.tpl");
        Route::get("/error","felta/shop/error.tpl");
        Route::get("/shoppingcart","felta/shop/order.tpl");
        Route::get("/order/{oid}","felta/shop/order.tpl");
        Route::get("/return","/felta/shop/return.tpl");

    });

    Route::get("/status", function () { FeltaController::GET_STATUS(); });
});

?>