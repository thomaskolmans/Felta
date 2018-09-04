<html>
    <head>
       <title>Felta | Order</title>
       <link href="/felta/stylesheets/all.css" rel="stylesheet">
       <link href="/felta/js/quill/quill.snow.css" rel="stylesheet">
       <link rel="icon" href="/felta/images/black.png" type="image/png" />
       <link rel="stylesheet" href="/felta/fonts/font-awesome.min.css" />
       <link rel="stylesheet" href="/felta/fonts/font-awesome.css" />
       <script src="/felta/js/jquery-1.11.3.min.js"></script>
       <script src="/felta/js/Chart.min.js"></script>
       <meta charset="utf-8">
       <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body> 
        <?php
            use lib\Shop\Order;
            use lib\Shop\OrderStatus;
            use lib\Shop\Shop;
            use lib\Shop\ShopItem;
            use lib\Shop\ShopItemVariant;
            use lib\Shop\Shoppingcart;
            use lib\Shop\Customer;
            use lib\Felta;
            
            if(isset($_GET["oid"])){
                $oid = $_GET["oid"];
                if(Order::exists($oid)){
                    $order = Order::get($oid);
                    ?>
                    <div class="main container no-top settings">
                        <h1>Order <i>#<?php echo $oid; ?></i></h1>
                        <table class="bill big">
                            <tr>
                                <td colspan="2">Order:</td>
                                <td colspan="3"> #<span id="order-id"><?php echo $oid; ?></span></td>
                            </tr>
                            <tr>
                                <td colspan="2">Customer:</td>
                                <?php 
                                if(Felta::getInstance()->user->hasSession()){
                                
                                    echo '<td colspan="3"><a href="/felta/shop/customer/'.$order->getCustomer().'" >#'.$order->getCustomer().'</a></td>';
                                }else{
                                    echo '<td colspan="3">#'.$order->getCustomer().'</td>';
                                }
                                ?>
                            </tr>
                            <tr><td></td><td></td></tr>
                            <tr><td></td><td></td></tr>
                            <tr>
                                <th>N</th>
                                <th>Image</th>
                                <th>Price</th>
                                <th>Name</th>
                                <th>Quantity</th>
                            </tr>
                    <?php
                        $items = $order->getShopitems();
                        $iteration = 0;
                        if(count($items) > 0){
                            foreach($items as $item => $quantity){
                                $shopitemvariant = ShopItemVariant::get($item);
                                $images = $shopitemvariant->getImages();
                                $image = count($images) > 0 ? $images[0] : "";
                                $shopitem = ShopItem::getSolo($shopitemvariant->getSid());
                                echo '
                                 <tr>
                                    <td item-id="'.$shopitemvariant->getId().'">'.($iteration +1).'</td>
                                    <td>
                                        <img src="'.$image.'">
                                    </td>
                                    <td><span>€</span>'.Shop::intToDouble($shopitemvariant->getPrice()).'</td>
                                    <td>'.$shopitem->getName().'</td>
                                    <td>'.$quantity.'</td>
                                    <td><div class="delete" onclick="removeItem(\''. $shopitemvariant->getId().'\')"></div></td>
                                 </tr>';
                                $iteration++;
                            }
                            echo '
                                <table class="bill right">
                                    <hr>
                                    <tr>
                                        <th>Shipping costs</th>
                                        <td>€ '.Shop::intToDouble($order->getShippingCost()).'</td>
                                    </tr>
                                    <tr>
                                        <th>Btw</th>
                                        <td>€ '.Shop::intToDouble($order->getBtw($order->getTotalAmount(),true)).'</td>
                                    </tr>
                                    <tr>
                                        <th>Total</th>
                                        <td >€ <span id="totalAmount">'.Shop::intToDouble($order->getTotalAmount()).'</span></td>
                                    </tr>
                                </table>
                            ';
                        }else{
                            echo 'Order is empty';
                        }
                        ?>
                        </table>
                        <a href="/"><button class="black">Home</button></a>
                        <?php
                            if($order->getOrderStatus() < OrderStatus::PAYED){
                                echo '<a href="/felta/shop/pay/'.$oid.'" > <button> Go to checkout </button></a>'; 
                            }
                        ?>
                    </div>
                    <script src="/felta/js/shop.js"></script>
                    <?php
                }else{
                    echo "<h1>Sorry this order doesn't exists</h1>";exit;
                }
            }else{
        ?>
                <div class="main container no-top settings shoppingcart">
                    <h1>Shoppingcart</i></h1>
                    <table class="cart">
                    <?php
                        $shoppingcart = new Shoppingcart($_COOKIE["SCID"]);
                        $items = $shoppingcart->pull()->getItems();
                        $iteration = 0;
                        if(count($items) > 0){
                            foreach($items as $item => $quantity){
                                $shopitemvariant = ShopItemVariant::get($item);
                                $images = $shopitemvariant->getImages();
                                $image = count($images) > 0 ? $images[0] : "";
                                $shopitem = ShopItem::getSolo($shopitemvariant->getSid());
                                echo '
                                 <tr>
                                    <td item-id="'.$shopitemvariant->getId().'">'.($iteration +1).'</td>
                                    <td>
                                        <img src="'.$image.'">
                                    </td>
                                    <td>€'.Shop::intToDouble($shopitemvariant->getPrice()).'</td>
                                    <td>'.$shopitem->getName().'</td>
                                    <td>
                                        <input type="button" value="-" id="moins" onclick="mi('.$iteration.', \''.$shopitemvariant->getId().'\')" />
                                        <input type="text" size="25" value="'.$quantity.'" id="amount'.$iteration.'" >
                                        <input type="button" value="+" id="plus" onclick="plu('.$iteration. ' ,\''. $shopitemvariant->getId().'\')"/>
                                    </td>
                                    <td><div class="delete" onclick="removeItem(this,\''. $shopitemvariant->getId().'\')"></div></td>
                                 </tr>';
                                $iteration++;
                            }
                            echo '
                                <table class="bill right">
                                    <hr>
                                    <tr>
                                        <th>Shipping costs</th>
                                        <td>€ '.Shop::intToDouble($shoppingcart->getShippingCost()).'</td>
                                    </tr>
                                    <tr>
                                        <th>Btw</th>
                                        <td>€ '.Shop::intToDouble($shoppingcart->getBtw($shoppingcart->getTotalAmount(),true)).'</td>
                                    </tr>
                                    <tr>
                                        <th>Total</th>
                                        <td >€ <span id="totalAmount">'.Shop::intToDouble($shoppingcart->getTotalAmount()).'</span></td>
                                    </tr>
                                </table>
                            ';
                        }else{
                            echo 'Shoppingcart is empty';
                        }

                     ?>
                    </table>
                    <a href="/"><button id="back" class="black">Back</button></a>
                    <?php
                        if(count($items) > 0){
                            echo'<a href="/felta/shop/checkout/'.$_COOKIE["SCID"].'"> 
                                    <button id="checkout"> Go to checkout </button>
                                </a>';
                        }

                    ?> 
                    <span id="message"></span>
                </div>
                <script src="/felta/js/shop.js"></script>
                <script type="text/javascript">
                    function mi(i,item){
                        minus(i,item).then(function(response){
                            window.location.reload();
                        });
                    }
                    function plu(i,item){
                        plus(i,item).then(function(response){
                            window.location.reload();
                        });
                    }
                    function removeItem(e,item){
                        deleteShoppingcartItem(item);
                        $(e.parentElement.parentElement).remove();
                        window.location.reload();
                    }
                    $("#back").on("click",function(e){
                        e.preventDefault();
                        window.location = "/";
                    });
                    getShopppingCart();
                </script>
        <?php } ?>
    </body>
</html>