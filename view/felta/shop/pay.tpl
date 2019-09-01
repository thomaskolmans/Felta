<html>
    <head>
       <title>Felta | Checkout</title>
       <meta http-equiv="Cache-Control" content="no-store" />
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
            use lib\shop\Shop;
            use lib\shop\order\Order;
            use lib\shop\order\Customer;
            use lib\shop\Shoppingcart;
            
            if(isset($_GET["oid"])){
                $oid = $_GET["oid"];
                if(Order::exists($oid)){
                    $order = Order::get($oid);
                    $customer = Customer::get($order->getCustomer());
                    $name = $customer->firstname." ".$customer->lastname;
        ?>              
        <div class="window">
            <h1>Checkout</h1>
            <table class="bill big">
                <tr>
                    <td>Order:</td>
                    <td><?php echo '<a href="/felta/shop/order/'.$oid.'">#'.$oid; ?></a></td>
                </tr>
                <tr>
                    <td>Customer:</td>
                    <td>#<?php echo $order->getCustomer();?> </td>
                </tr>
                <tr>
                    <td>Total amount:</td>
                    <td>€<span id="amount"><?php echo Shop::intToDouble($order->getTotalAmount()); ?></span></td>
                </tr>
            </table>
            <div class="relative">
                <div>
                    <div class="tabs center">
                        <div class="tab" id="card-tab" column="card">Creditcard</div>
                        <div class="tab" id="ideal-tab" column="ideal">IDEAL</div>
                    </div>
                    <section class="hidden" id="card">
                        <form  method="post" class="nothing" id="card-form">
                          <div class="form-row">
                            <div id="card-element"></div>
                            <div class="msg" id="card-errors" role="alert"></div>
                          </div>
                        </form>
                    </section>
                    <section class="hidden" id="ideal">
                        <div class="bank-options">
                            <div class="bank-option" value="abn_amro"><img src="/felta/images/banks/abn_amro.jpg"/></div>
                            <div class="bank-option" value="asn_bank"><img src="/felta/images/banks/asn.png"/></div>
                            <div class="bank-option" value="bunq"><img src="/felta/images/banks/bunq.jpg"/></div>
                            <div class="bank-option" value="ing"><img src="/felta/images/banks/ing.jpg"/></div>
                            <div class="bank-option" value="knab"><img src="/felta/images/banks/knab.jpg"/></div>
                            <div class="bank-option" value="rabobank"><img src="/felta/images/banks/rabobank.jpg"/></div>
                            <div class="bank-option" value="regiobank"><img src="/felta/images/banks/regiobank.png"/></div>
                            <div class="bank-option" value="sns_bank" ><img src="/felta/images/banks/sns.png"/></div>
                            <div class="bank-option" value="triodos_bank"><img src="/felta/images/banks/triodos.png"/></div>
                            <div class="bank-option" value="van_lanschot"><img src="/felta/images/banks/van_lancshot.jpg"/></div>
                        </div>
                    </section>
                    <div class="input-group right right-buttons">
                        <?php echo '<a href="/felta/shop/order/'.$oid.'"><button class="add" id="order">See order</button></a>'; ?>
                        <button class="highlighted" id="next">Checkout</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="/felta/js/shop.js"></script>
        <script type="text/javascript">
            var active = "card";
            var last = "card";
            $("#"+active).removeClass("hidden");
            $("#"+active+"-tab").addClass("active");
            $(".tab").on("click",function(e){
                last = active;
                $("#"+last).addClass("hidden");
                $("#"+last+"-tab").removeClass("active");
                active = $(this).attr("column");
                $("#"+active).removeClass("hidden");
                $("#"+active+"-tab").addClass("active");
            });

            /* ideal selection */
            $(".bank-option").on("click",function(e){
                $(".bank-option.selected").removeClass("selected");
                $(this).addClass("selected");
            });
            /*stripe element */
            var style = {
              base: {
                fontSize: '18px',
                lineHeight: '24px',
                color: '#ffffff'
              },
            };
            var oid = window.location.pathname.split("/").pop();
            var card = elements.create('card', {style});
            card.mount('#card-element');

            $("#next").on("click",function(e){
                e.preventDefault();
                var bank = $(".bank-option.selected").attr("value");
                switch(active){
                    case "card":
                        card(oid,card);
                    break;
                    case "ideal":
                        ideal(oid,bank);
                    break;
                    case "paypal":
                        paypal(oid);
                    break;
                }
            });

        </script>
        <?php
                }else if(Shoppingcart::exists($oid)){
                    ?>
                    <div class="window order">
                        <h1>Checkout</h1>
                          <form method="post" id="user" action="/felta/shop/create/order">
                            <div class="input-container">
                                <div class="input-group">
                                    <div class="input-group half">
                                        <input type="text" id="firstname" name="firstname" />
                                        <label>Firstname</label>
                                    </div>
                                    <div class="input-group half">
                                        <input type="text" id="lastname" name="lastname" />
                                        <label>Lastname</label>
                                    </div>
                                </div>
                                <div class="input-group more">
                                    <input type="text" id="email" name="email" />
                                    <label>Email address</label>
                                </div>
                                <div class="input-group more">
                                    <div class="input-group eighty">
                                      <input type="text" name="street" id="street" />
                                      <label>Street</label>
                                    </div>
                                    <div class="input-group fifth">
                                      
                                      <input type="text" name="number" id="number" />
                                      <label>Number</label>
                                    </div>
                                </div>
                                <div class="input-group">
                                    <div class="input-group half">
                                      <input type="text" name="zipcode" id="zipcode" />
                                      <label>Zipcode</label>
                                    </div>
                                    <div class="input-group half">
                                      <input type="text" name="city" id="city" />
                                      <label>City</label>
                                    </div>
                                </div>
                                <div class="input-group half">
                                  <input type="text" name="country" id="country" />
                                  <label>Country</label>
                                </div>
                                <div class='input-group right'>
                                    <a href="/felta/shop/shoppingcart"><button id="cancel" class="black">Cancel</button></a>
                                    <input type="submit" id="save" name="add-user" value="Next"/>
                                </div>
                            </div>
                          </form>
                    </div>
                    <?php
                }else{
                    echo "<h1>Sorry this order or shoppingcart doesn't exist doesn't exists</h1>";exit;
                }
            }else{
                echo "<h1>Sorry you have to provide an order id</h1>"; exit;
            }
        ?>
    <script type="text/javascript">
        var $firstname = $("#firstname");
        var $lastname = $("#lastname");

        var $email = $("#email");

        var $street = $("#street");
        var $housenumber = $("#number");
        var $zipcode = $("#zipcode");
        var $city = $("#city");
        var $country = $("#country");

        var values = [$firstname,$lastname,$email,$street,$housenumber,$zipcode,$city,$country];

        $("#user").on("submit", function(e){
            var isValid = true;
            for(var i = 0; i < values.length; i++){
                var value = values[i];
                var name = value.next("label").text().toLowerCase();
                if(value.val() == ''){
                    isValid = false;
                    value.parent().children("span").remove();
                    value.parent().append("<span class='msg'>You have to fill in your "+name+"</span>");
                }
            }
            if(!isValid){
                e.preventDefault();
            }
        });
        $('.input-group input,.input-group textarea').on('input blur change',function(){
            let text_val = $(this).val();
            if(text_val === "") {
                $(this).removeClass('has-value');
            } else {
                $(this).addClass('has-value');
            }
        });
        $('.input-group input,.input-group textarea').each(function(i){
            if($(this).val()){
                $(this).addClass("has-value");
            }
        });
        $("#cancel").on("click",function(e){
            e.preventDefault();
            window.location = "/felta/shop/shoppingcart";
        });
    </script>
    </body>
</html>