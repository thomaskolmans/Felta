<?php
use lib\Cleverload;
use lib\Felta;
use lib\Routing\Route;
use lib\Post\Edit;
use lib\Helpers\Input;

use lib\Post\Agenda;
use lib\Post\News;
use lib\Filesystem\type\Image;

use lib\Shop\Shop;
use lib\Shop\Shoppingcart;
use lib\Shop\Promotion;
use lib\Shop\Order;
use lib\Shop\OrderStatus;
use lib\Shop\ShopItem;
use lib\Shop\ShopItemVariant;
use lib\Shop\Customer;
use lib\Shop\CustomerAddress;
use lib\Shop\Transaction;
use lib\Shop\Payment;

$felta = Felta::getInstance();
$user = $felta->user;
$edit = new Edit();
$agenda = new Agenda();
$news = new News();

Route::group(["namespace" => "/felta"], function() use ($user,$edit,$felta,$agenda,$news){
    if($felta->user->hasSession()){
        /* logged in pages */
        Route::any("/dashboard","felta/dashboard.tpl")->primary();
        Route::get("/editor","felta/editor.tpl");
        Route::get("/social","felta/social.tpl");
        Route::get("/settings","felta/settings.tpl");
        Route::get("/agenda","felta/agenda.tpl");
        Route::get("/agenda/id/update","felta/agenda.tpl");
        Route::get("/news","felta/news.tpl");
        Route::get("/blog","felta/blog_main.tpl");
        Route::post("/settings",function() use ($felta){
            $user = $felta->user;
            if(isset($_POST['addition'])){
                $username = $_POST['username'];
                $email = $_POST['email'];
                if($user->createWithPassword($username,$email)){
                    echo "<div class='message'>User had been added!</div>";
                }else{
                    echo "<div class'message'>Sorry, something went wrong</div>";
                }
            }
            if(isset($_POST['changepassword'])){
                $old = $_POST['old_password'];
                $new = $_POST['new_password'];
                $repeat = $_POST['repeat_new_password'];
                if($user->resetPassword($old,$new,$repeat)){
                    echo "Password has changed";
                }else{
                    echo "Either wrong password or new passwords didn't match";
                }
            }
            if(isset($_POST['general'])){
                $felta->settings->set('website_url',$_POST['website_url']);
                $felta->settings->set('website_name',$_POST['website_name']);
                $felta->settings->set('default_dir',$_POST['default_dir']);
            }
        });
        Route::post("/status",function() use ($felta){
            $online = $_POST["online"];
            $felta->setStatus($online);
        });
        Route::post("/settings/delete/user",function() use ($felta){
            $user = $felta->user;
            $user->delete($_POST['id']);
        });
        Route::post("/news",function() use ($news){
            $agenda->put($_POST['title'],$_POST['description'],null,new \DateTime($_POST['date']));
            header("Location: /Felta/news");
        });
        Route::post("/news/update",function() use($news){
            $id = $_POST['id'];
            $news->update('title',['id' => $id],$_POST['title']);
            $news->update('description',['id' => $id],$_POST['description']);
            $date = new \DateTime($_POST['date']);
            $news->update('date',['id' => $id],$date->format("Y-m-d H:i:s"));
            header("Location: /Felta/news");
        });
        Route::get("/news/delete/{id}",function($id) use ($news){
            $news->delete(["id"=>$id]);
            header("Location: /Felta/news");
        });

        Route::post("/agenda",function() use ($agenda){
            $agenda->put(
                $_POST['title'],
                $_POST['description'],
                null,
                $_POST['location'],
                new \DateTime($_POST['from']),
                new \DateTime($_POST['until'])
            );
            header("Location: /Felta/agenda");
        });
        Route::post("/agenda/update",function() use($agenda){
            $id = $_POST['id'];
            $agenda->update('title',['id' => $id],$_POST['title']);
            $agenda->update('description',['id' => $id],$_POST['description']);
            $agenda->update('location',['id' => $id],$_POST['location']);
            $from = new \DateTime($_POST['from']);
            $until = new \DateTime($_POST['until']);
            $agenda->update('from',['id' => $id],$from->format("Y-m-d H:i:s"));
            $agenda->update('until',['id' => $id],$until->format("Y-m-d H:i:s"));
            header("Location: /Felta/agenda");
        });
        Route::get("/agenda/delete/{id}",function($id) use ($agenda){
            $agenda->delete(["id"=>$id]);
            header("Location: /Felta/agenda");
        });
        Route::get("/logout",function(){
            $user = Felta::getInstance()->user;
            $user->logout();
            header("Location: /Felta/login");
        });
        Route::get("/edit/id/{id}/lang/{language}",function($id,$language) use ($edit){
            echo $edit->get($id,$language);
        });
        Route::post("/edit",function() use ($edit){
            $text = $_POST["text"];
            $id = $_POST["id"];
            $language = $_POST["language"];
            $edit->setText($id,$language,$text);
        });
        Route::post("/edit/image",function() use ($edit){
            $w = Input::value("w");
            $h = Input::value("h");
            $x = Input::value("x1");
            $y = Input::value("y1");
            $x2 = Input::value("x2");
            $y2 = Input::value("y2");
            $id = Input::value("id");
            $lang = Input::value("language");
            $file =  new lib\Filesystem\File($_FILES["file_name"],null);
            $image = $file->getTmpFile();
            if($file->upload(null)){
                lib\Filesystem\type\Image::resize($file->getDestination(),$x,$y,$w,$h,$x2,$y2);
            }
            $edit->setText($id,$lang,$file->getRelativeDest());
            echo $file->getRelativeDest();
        });
        Route::delete("/language/{lng}",function($lng) use ($edit){
            $edit->language->remove($lng); 
        });
        Route::group(["namespace" => "/statistics"],function(){
            Route::get("/","felta/statistics.tpl");
            Route::get("/unique",function() {
                echo json_encode(Felta::getInstance()->getSQL()->execute("select count(*) from visitors_unique"));
            });
            Route::get("/unique/today",function(){
                echo json_encode(Felta::getInstance()->getSQL()->execute("SELECT date,count(*) FROM visitors_unique WHERE date >= NOW() - INTERVAL 1 DAY GROUP BY hour( date ) , day( date ) ORDER BY date"));
            });
            Route::get("/total",function() {
                echo json_encode(Felta::getInstance()->getSQL()->execute("select count(*) from visitors_total"));
            });
            Route::get("/total/today",function(){
                echo json_encode(Felta::getInstance()->getSQL()->execute("SELECT date,count(*) FROM visitors_total WHERE date >= NOW() - INTERVAL 1 DAY GROUP BY hour( date ) , day( date ) ORDER BY date"));
            });  
        });

        Route::group(["namespace" => "/shop"],function(){
            Route::get("/","felta/shop/dashboard.tpl");
            Route::get("/orders/{from}/{until}","felta/shop/orders.tpl");
            Route::get("/items", "felta/shop/items.tpl");
            Route::get("/add/item","felta/shop/addShopItem.tpl");
            Route::get("/update/item/{id}","felta/shop/updateShopItem.tpl");
            Route::get("/settings","felta/shop/settings.tpl");
            Route::get("/customer/{cid}","felta/shop/customer.tpl");
            Route::get("/transaction/{tid}","felta/shop/transaction.tpl");
            Route::get("/transactions/{from}/{until}","felta/shop/transactions.tpl");

            Route::post("/add/item",function(){
                $name = $_POST["name"];
                $catagory = $_POST["catagory"];
                $description = $_POST["description"];
                $image = null;
                $shopitem = ShopItem::create($name,$catagory,$description,$image,true);

                $variants = 1;
                $ItemVariants = [];
                for($i = 1; $i < $variants + 1; $i++){

                    $amount = $_POST["amount".$i];
                    $currency = $_POST["currency".$i];
                    $variables = $_POST["variables".$i];
                    $quantity = $_POST["quantity".$i];
                    $images = $_POST["images"];

                    $ItemVariants[] = ShopItemVariant::create(
                        $shopitem->getId(),
                        str_replace([",","."],["",""],$amount),
                        $currency,
                        $images,
                        $quantity,
                        $variables
                    );
                }

                $shopitem->setVariants($ItemVariants);
                $shopitem->save();
                header("Location: /felta/shop/");
            });
            Route::post("/update/item",function(){
                $id = $_POST["id"];
                $shopitem = ShopItem::get($id);

                $shopitem->setName($_POST["name"]);
                $shopitem->setCatagory($_POST["catagory"]);
                $shopitem->setDescription($_POST["description"]);
                $shopitem->update();

                $variants = 1;
                $ItemVariants = [];
                $i = 1;
                foreach($shopitem->getVariants() as $variant){
                    $amount = $_POST["amount".$i];
                    $currency = $_POST["currency".$i];
                    $variables = $_POST["variables".$i];
                    $quantity = $_POST["quantity".$i];
                    $images = isset($_POST["images"]) ? $_POST["images"] : [];
                    $variant->setPrice(str_replace([",","."],["",""],$amount));
                    $variant->setCurrency($currency);
                    $variant->setImages($images);
                    $variant->setQuantity($quantity);
                    $variant->setVariables($variables);
                    $variant->update();
                    $i++;
                }
                $shopitem->setVariants($ItemVariants);
                $shopitem->update();
                header("Location: /felta/shop/");
            });
            Route::get("/delete/item/{id}",function($id){
                $shopitem = ShopItem::get($id);
                $shopitem->delete();
                header("Location: /felta/shop");
            });

            Route::post("/add/catagory",function(){
                Shop::getInstance()->addCatagory($_POST["catagory"]);
                header("Location: /felta/shop");
            });
            Route::post("/delete/catagory",function(){
                Shop::getInstance()->deleteCatagory($_POST["catagory"]);
                header("Location: /felta/shop");
            });

            Route::post("/upload/image",function(){
                $uid = \lib\Helpers\UUID::generate(20);
                $file =  new lib\Filesystem\File($_FILES["picture"],null);
                $file->setExtension("png");
                $file->setName($uid);
                $file->upload(null);
                echo json_encode(["url" => $file->getRelativeDest(),"uid"=>$uid]);
            });
            Route::post("/delete/image",function(){
                $url = $_POST["url"];
                Shop::deleteImage($url);
            });
            Route::post("/delete/promotion",function(){

            });
            Route::post("/add/promotion",function(){

            });
            Route::post("/address",function(){
                Shop::getInstance()->updateShopAddress(
                    htmlspecialchars($_POST["street"], ENT_QUOTES, 'UTF-8'),
                    htmlspecialchars($_POST["number"], ENT_QUOTES, 'UTF-8'),
                    htmlspecialchars($_POST["zipcode"], ENT_QUOTES, 'UTF-8'),
                    htmlspecialchars($_POST["city"], ENT_QUOTES, 'UTF-8'),
                    htmlspecialchars($_POST["country"], ENT_QUOTES, 'UTF-8')
                );
                header("Location: /felta/shop/settings");
            });
            Route::post("/settings",function(){
                Shop::getInstance()->updateSettings(
                    htmlspecialchars($_POST["url"], ENT_QUOTES, 'UTF-8'),
                    htmlspecialchars($_POST["btw"], ENT_QUOTES, 'UTF-8'),
                    htmlspecialchars(boolval($_POST["exclbtw"]), ENT_QUOTES, 'UTF-8'),
                    htmlspecialchars(boolval($_POST["shipping"]), ENT_QUOTES, 'UTF-8'),
                    htmlspecialchars(boolval($_POST["freeshipping"]), ENT_QUOTES, 'UTF-8')
                );
                header("Location: /felta/shop/settings");    
            });
            Route::post("/shipping",function(){
                Shop::getInstance()->updateShipping(
                    Shop::doubleToInt($_POST["amount"]),
                    htmlspecialchars($_POST["ipp"], ENT_QUOTES, 'UTF-8')
                );
                header("Location: /felta/shop/settings");    
            });
        });
    }else{
        Route::get("/login","felta/login.tpl")->primary();
        Route::any("/forgot","felta/reset.tpl");
        Route::get("/forgot/code/{code}","felta/reset.tpl");
        Route::post("/login",function() use ($user){
            if($user->login($_POST["username"],$_POST["password"],$_POST["remember"])){
                echo json_encode(["logged_in" => true,"message" =>"Succesfull login"]);
            }else{
                echo json_encode(["logged_in" => false,"message" =>"Incorrect username and/or password"]);
            }
        });
        Route::get("/user/verify/{key}",function($key) use ($felta){
            $user = $felta->user;
            $user->verifyVerification($key);
            header("Location: /felta");
        });
    }
    Route::group(["namespace" => "/shop"],function(){
        Route::post("/charge",function(){
            $source = Payment::getSource($_POST["source"]);
            $orderid = $_POST["oid"];
            $order = Order::get($orderid);
            $amount = $order->getTotalAmount();
            $method = $_POST["method"];
            $currency = "eur";
            $payment = new Payment($orderid,$source,$method,$amount,$currency,"");
            $payment->pay();
            echo json_encode($payment);
        });
        Route::post("/charge/source",function(){
            echo json_encode(Payment::chargeFromSource($_POST["source"]));
        });
        Route::post("/transaction",function(){
            $transaction = Transaction::create(
                htmlspecialchars($_POST["sid"], ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($_POST["oid"], ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($_POST["method"], ENT_QUOTES, 'UTF-8'),                
                htmlspecialchars($_POST["state"], ENT_QUOTES, 'UTF-8'),
                new \DateTime()
            );
            $transaction->save();
            echo json_encode($transaction);
        });

        Route::post("/webhook",function(){
            $input = @file_get_contents("php://input");
            $json = json_decode($input,true);
            echo json_encode(Payment::chargeFromSource($json["data"]["object"]));
        });

        Route::post("/create/order",function(){
            $customer = Customer::create(
                htmlspecialchars($_POST["firstname"], ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($_POST["lastname"], ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($_POST["email"],ENT_QUOTES,'UTF-8'),
                htmlspecialchars($_POST["street"], ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($_POST["number"], ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($_POST["zipcode"], ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($_POST["city"], ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($_POST["country"], ENT_QUOTES, 'UTF-8'),
                false,
                false,
                false
            );
            $customer->save();
            $order = Order::createFromShoppingcart(new Shoppingcart($_COOKIE["SCID"]),$customer->id);
            $order->save();
            header("Location: /felta/shop/pay/".$order->getId());
        });
        Route::get("/create/shoppingcart",function(){echo json_encode(Shoppingcart::create()->getId());});
        Route::post("/shoppingcart/order",function(){
            $customer = htmlspecialchars($_POST["customer"], ENT_QUOTES, 'UTF-8');
        });
        Route::get("/catagories",function(){echo json_encode(Shop::getInstance()->getCatagories());});


        Route::post("/add/shoppingcart",function(){
            $shoppingcart = new Shoppingcart($_COOKIE["SCID"]);
            $shoppingcart->set($_POST["item"],$_POST["quantity"]);
            $shoppingcart->save();
            echo json_encode(["amount" => $shoppingcart->getTotalAmount()]);
        });
        Route::post("/update/shoppingcart",function(){
            $shoppingcart = new Shoppingcart($_COOKIE["SCID"]);
            $shoppingcart->update($_POST["item"],$_POST["quantity"]);
            echo json_encode(["amount" => $shoppingcart->getTotalAmount()]);
        });
        Route::post("/delete/shoppingcart",function(){
            $shoppingcart = new Shoppingcart($_COOKIE["SCID"]);
            $shoppingcart->delete($_POST["item"]);
        });

        Route::get("/return","/felta/shop/return.tpl");
        
        /* Default UI for websites */
        Route::get(["/pay/{oid}","/checkout/{oid}"],"felta/shop/pay.tpl");
        Route::get("/thankyou/{oid}","felta/shop/thankyou.tpl");
        Route::get("/error","felta/shop/error.tpl");
        Route::get("/shoppingcart","felta/shop/order.tpl");
        Route::get("/order/{oid}","felta/shop/order.tpl");
    });
    Route::get("/status",function() use ($felta){
        echo $felta->getStatus();
    });
});

?>