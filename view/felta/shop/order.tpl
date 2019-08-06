<html>
    <head>
       <title>Felta | Order</title>
       <link href="/felta/stylesheets/shop.css" rel="stylesheet">
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
            use lib\Shop\Product;
            use lib\Shop\ProductVariant;
            use lib\Shop\Shoppingcart;
            use lib\Shop\Customer;
            use lib\Felta;
            
            if(isset($_GET["oid"])){
                $oid = $_GET["oid"];
                if(Order::exists($oid)){
                    $order = Order::get($oid);
                    ?>
                    <div class="window">
                        <h1>Order <i>#<?php echo $oid; ?></i></h1>

                        <table class="bill big">
                            <tr>
                                <th colspan="2">Order:</th>
                                <td colspan="3"> #<span id="order-id"><?php echo $oid; ?></span></td>
                            </tr>
                            <?php 
                                if(Felta::getInstance()->user->hasSession()){
                                    echo '
                                        <tr>
                                            <th colspan="2">Status:</th>
                                            <td colspan="3">';
                                                switch($order->getOrderstatus()){
                                                case 0:
                                                    echo "In progress....";
                                                    break;
                                                case 1:
                                                    echo "Paid";
                                                    break;
                                                }
                                            echo '</td>
                                        </tr>';
                                }
                            ?>
                            <tr>
                                <th colspan="2"></th>
                                <?php 
                                    if(Customer::exists($order->getCustomer())){
                                        $customer = Customer::get($order->getCustomer());
                                        $address = $customer->address;
                                        ?>

                                        <tr>
                                            <th colspan="2">Name:</th>
                                            <td colspan="3"><?php echo $customer->firstname." ".$customer->lastname; ?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Email:</th>
                                            <td colspan="3"><?php echo $customer->email; ?></td>
                                        </tr>
                                        <tr><td colspan="2"></td></tr>
                                        <tr>
                                            <th colspan="2">Street:</th>
                                            <td colspan="3"><?php echo $address->getStreet(); ?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Number:</th>
                                            <td colspan="3"><?php echo $address->getNumber(); ?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Zipcode:</th>
                                            <td colspan="3"><?php echo $address->getZipcode(); ?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">City:</th>
                                            <td colspan="3"><?php echo $address->getCity(); ?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Country:</th>
                                            <td colspan="3"><?php echo $address->getCountry(); ?></td>
                                        </tr>
                                <?php
                                    } else {
                               
                                        echo '<tr>
                                                <th colspan="2">Customer:</th>
                                                <td colspan="3"><i>Does not exist... Something has gone wrong!</i></td>
                                            </tr>';

                                    }
                                ?>
                            </tr>
                            <tr><td></td><td></td></tr>
                            <tr><td></td><td></td></tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Price</th>
                                <th>Product</th>
                                <th>Quantity</th>
                            </tr>
                            <?php
                                $items = $order->getShopitems();
                                $iteration = 0;
                                if(count($items) > 0){
                                    foreach($items as $item => $quantity){
                                        $productvariant = ProductVariant::get($item);
                                        $images = $productvariant->getImages();
                                        $image = count($images) > 0 ? $images[0] : "";
                                        $product = Product::getSolo($productvariant->getSid());
                                        echo '
                                         <tr>
                                            <td item-id="'.$productvariant->getId().'">'.($iteration +1).'</td>
                                            <td>
                                                <img src="'.$image.'">
                                            </td>
                                            <td><span>€</span>'.Shop::intToDouble($productvariant->getPrice()).'</td>
                                            <td>'.$product->getName().'</td>
                                            <td>'.$quantity.'</td>
                                            <td><div class="delete" onclick="removeItem(\''. $productvariant->getId().'\')"></div></td>
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
                                                <th>Subtotal</th>
                                                <td>€ '.Shop::intToDouble($order->getSubTotal()).'</td>
                                            </tr>
                                            <tr>
                                                <th>VAT</th>
                                                <td>€ '.Shop::intToDouble($order->getBtw($order->getSubTotal(),true)).'</td>
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
                            <div class="right-buttons">
                                <?php
                                    if(!Felta::getInstance()->user->hasSession()){
                                        echo ' <a href="/"><button class="black">Home</button></a>';
                                    }

                                    if(Felta::getInstance()->user->hasSession()){
                                        echo ' <button class="print" onClick="window.print()">Print</button>';
                                    } 
                                    if($order->getOrderStatus() < OrderStatus::PAID){
                                        echo ' <a href="/felta/shop/pay/'.$oid.'" ><button class="highlighted"> Go to checkout </button></a>'; 
                                    }
                                ?>
                            </div>
                        </table>

                    </div>
                    <script src="/felta/js/shop.js"></script>
                <?php
                    }else{
                        echo "<h1>Sorry this order doesn't exists</h1>";exit;
                    }
                }else{
                ?>
                <div class="window shoppingcart">
                    <h1>Shoppingcart</i></h1>
                    <table class="cart">
                    <?php
                        $shoppingcart = new Shoppingcart($_COOKIE["SCID"]);
                        $items = $shoppingcart->pull()->getItems();
                        $iteration = 0;
                        if(count($items) > 0){
                            foreach($items as $item => $quantity){
                                $productvariant = ProductVariant::get($item);
                                $images = $productvariant->getImages();
                                $image = count($images) > 0 ? $images[0] : "";
                                $product = Product::getSolo($productvariant->getSid());
                                echo '
                                 <tr>
                                    <td>
                                        <img src="'.$image.'">
                                    </td>
                                    <td>€'.Shop::intToDouble($productvariant->getPrice()).'</td>
                                    <td>'.$product->getName().'</td>
                                    <td>
                                        <input type="button" value="-" id="moins" onclick="mi('.$iteration.', \''.$productvariant->getId().'\')" />
                                        <input type="text" size="25" value="'.$quantity.'" id="amount'.$iteration.'" >
                                        <input type="button" value="+" id="plus" onclick="plu('.$iteration. ' ,\''. $productvariant->getId().'\')"/>
                                    </td>
                                    <td><div class="delete" onclick="removeItem(this,\''. $productvariant->getId().'\')"></div></td>
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
                    <div class="right-buttons">
                        <a href="/"><button id="back" class="black">Back</button></a>
                        <?php
                            if(count($items) > 0){
                                echo'<a href="/felta/shop/checkout/'.$_COOKIE["SCID"].'"> 
                                        <button class="highlighted" id="checkout"> Go to checkout </button>
                                    </a>';
                            }

                        ?> 
                    </div>
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