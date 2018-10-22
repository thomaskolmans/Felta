</html>
<head>
   <title>Felta | Shop settings</title>
   <link href="/felta/stylesheets/main.css" rel="stylesheet">
   <link href="/felta/js/quill/quill.snow.css" rel="stylesheet">
   <link rel="icon" href="/felta/images/black.png" type="image/png" />
   <link rel="stylesheet" href="/felta/fonts/font-awesome.min.css" />
   <link rel="stylesheet" href="/felta/fonts/font-awesome.css" />
   <script src="/felta/js/jquery-1.11.3.min.js"></script>
   <script src="/felta/js/dash.js"></script>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <script type="text/javascript">
        $(document).ready(function(){
            var active = "general";
            var last = "general";
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
            $("a[href^='#']").on("click",function(e){
                e.preventDefault();
                last = active;
                $("#"+last+"-tab").removeClass("active");
                $("#"+last).addClass("hidden");
                active = $(this).attr("column");
                $("#"+active).removeClass("hidden");
            });
        });
    </script>
</head>
<body>
   <include>felta/parts/nav.tpl</include>
   <div class="main settings">
      <h1>Shop Settings</h1>
      <div class="tabs">
          <div class="tab" id="general-tab" column="general">General</div>
          <div class="tab" id="address-tab" column="address">Shop address</div>
          <div class="tab" id="shipping-tab" column="shipping">Shipping</div>
      </div>
      <section class="hidden" id="general">
          <?php
            $shop = lib\Shop\Shop::getInstance();
            $settings = $shop->getSettings();
            $shopaddress = $shop->getShopAddress();
            $shippingSettings = $shop->getShipping();

            $street = isset($shopaddress["street"]) ? $shopaddress["street"] : "";
            $number = isset($shopaddress["number"]) ? $shopaddress["number"] : "";
            $zipcode = isset($shopaddress["zipcode"]) ? $shopaddress["zipcode"] : "";
            $city = isset($shopaddress["city"]) ? $shopaddress["city"] : "";
            $country = isset($shopaddress["country"]) ? $shopaddress["country"] : "";

            $url = isset($settings["url"]) ? $settings["url"] : "";
            $btw = isset($settings["btw"]) ? $settings["btw"] : "";

            $shipping = isset($settings["shipping"]) ? boolval($settings["shipping"]) : false;
            $exclbtw = isset($settings["exclbtw"]) ? boolval($settings["exclbtw"]) : false;
            $freeshipping = isset($settings["freeshipping"]) ? boolval($settings["freeshipping"]) : false;

            $shippingString = $shipping ?  "checked" : "";
            $freeshippingString = $freeshipping ? "checked" : "";
            $exclbtwString = $exclbtw ? "checked" : "";

            $amount = lib\Shop\Shop::intToDouble($shippingSettings["amount"]);
            $ipp = $shippingSettings["ipp"];
          ?>
          <form method="post" id="general" action="/felta/shop/settings">
            <div class="input-group">
              <label>Shop name</label>
              <?php echo '<input type="text" name="shop_name" id="shop_name" value="'.$shop->name.'" />'; ?>
            </div>
            <div class="input-group">
              <label>Shop url</label>
              <?php echo '<input type="text" name="url" id="shop_url" value="'.$url.'" />'; ?>
            </div>
            <div class="input-group">
              <label>Btw.</label>
              <?php echo '<input type="text" name="btw" id="shop_url" value="'.$btw.'" />'; ?>
            </div>
            <div class="input-group">
              <label>Exclusief Btw.</label>
              <label class="switch">
                <?php echo '<input type="checkbox" name="exclbtw" class="switcher" '.$exclbtwString.'>'; ?>
                <div class="slider round"></div>
              </label>
            </div>
            <div class="input-group">
              <label>Shipping</label>
              <label class="switch">
                <?php echo '<input type="checkbox" name="shipping" class="switcher" '.$shippingString.'>'; ?>
                <div class="slider round"></div>
              </label>
            </div>
            <div class="input-group">
              <label>Free shipping</label>
              <label class="switch">
                <?php echo '<input type="checkbox" class="switcher" name="freeshipping" '.$freeshippingString.'>'; ?>
                <div class="slider round"></div>
              </label>
            </div>
            <div class='input-group right'>
              <input type="submit" name="add-user" value="Save"/>
            </div> 
          </form>
      </section>
      <section class="hidden" id="address">
          <form method="post" action="/felta/shop/address" id="general">
            <div class="input-group">
              <label>Street</label>
              <?php echo '<input type="text" name="street" id="street" value="'.$street.'" />'; ?>
            </div>
            <div class="input-group">
              <label>Number</label>
              <?php echo '<input type="text" name="number" id="number" value="'.$number.'" />'; ?>
            </div>
            <div class="input-group">
              <label>Zipcode</label>
              <?php echo '<input type="text" name="zipcode" id="zipcode" value="'.$zipcode.'"/>'; ?>
            </div>
            <div class="input-group">
              <label>City</label>
              <?php echo '<input type="text" name="city" id="city" value="'.$city.'"/>'; ?>
            </div>
            <div class="input-group">
              <label>Country</label>
              <?php echo '<input type="text" name="country" id="country" value="'.$country.'"/>'; ?>
            </div>
            <div class='input-group right'>
              <input type="submit" name="add-user" value="Save"/>
            </div> 
          </form>
      </section>
      <section class="hidden" id="shipping">
          <form method="post" action="/felta/shop/shipping" id="general">
            <div class="input-group">
              <label>Amount</label>
              <?php echo '<input type="text" name="amount" id="amount" value="'.$amount.'" />'; ?>
            </div>
            <div class="input-group">
              <label>Items per package</label>
              <?php echo '<input type="text" name="ipp" id="ipp" value="'.$ipp.'" />'; ?>
            </div>
            <div class='input-group right'>
              <input type="submit" name="add-user" value="Save"/>
            </div> 
          </form>
      </section>
      <section class="hidden" id="methods">
          <form method="post" action="/felta/shop/shipping" id="general">
            <div class="input-group">
              <label>Amount</label>
              <?php echo '<input type="text" name="amount" id="amount" value="'.$amount.'" />'; ?>
            </div>
            <div class="input-group">
              <label>Items per package</label>
              <?php echo '<input type="text" name="ipp" id="ipp" value="'.$ipp.'" />'; ?>
            </div>
            <div class='input-group right'>
              <input type="submit" name="add-user" value="Save"/>
            </div> 
          </form>
      </section>
   </div>
</body>
</html>