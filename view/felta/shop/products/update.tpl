</html>
<head>
   <title>Felta | Update product</title>
   <link href="/felta/stylesheets/all.css" rel="stylesheet">
   <link href="/felta/js/croppie/croppie.css" rel="stylesheet">
   <link href="/felta/js/quill/quill.snow.css" rel="stylesheet">
   <link rel="stylesheet" type="text/css" href="/felta/js/datepicker/jquery.datetimepicker.css">
   <link rel="icon" href="/felta/images/black.png" type="image/png" />
   <link rel="stylesheet" href="/felta/fonts/font-awesome.min.css" />
   <link rel="stylesheet" href="/felta/fonts/font-awesome.css" />
   <script src="/felta/js/jquery-1.11.3.min.js"></script>
   <script src="/felta/js/croppie/croppie.min.js" type="text/javascript"></script>
   <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <include>felta/parts/nav.tpl</include>
  <div class="main dashboard container" id="main">
    <?php
      use lib\shop\Shop;
      use lib\shop\product\Product;
      use lib\shop\product\ProductVariant;

      $product;
      if(isset($_GET["id"])){
        $itemid = $_GET["id"];
        if(Product::exists($itemid)){
          $product = Product::get($itemid);
        } else {
          echo "<h1>Product does not exist</h1>";exit;
        }
      }
    ?>
    <h1>Update product</h1>

    <?php echo '<form method="post" class="full" id="new_item" action="/felta/shop/update/item/'.$product->getId().'">'; ?>
      <?php echo '<input type="hidden" name="id" placeholder="A product" value="'.$product->getId().'" />'; ?>
      <div class="input-group">
        <label>Name</label>
        <?php echo '<input type="text" name="name" placeholder="A product name" value="'.$product->getName().'"/>'; ?>
      </div>
    <div class="input-group">
        <label>Slug</label>
        <?php echo '<input type="text" name="slug" placeholder="Product slug" value="'.$product->getSlug().'" />'; ?>
      </div>
      <div class="input-group">
        <label>Category</label>
        <div class="select-box dark">
          <select name="category">
            <option disabled selected value="">-- select a category --</option>
            <?php              
              $shop = Shop::getInstance();
              $categories = $shop->getCategories();
              $activeCategory = $product->getCategory();
              var_dump($activeCategory);
              if($categories === null){ $categories = []; }
              if(count($categories) > 0){
                foreach($categories as $key => $category){
                  $name = $category["name"];
                  $selected = "";
                  if($name === $activeCategory){
                    $selected = "selected";
                  }
                  echo "<option value='{$name}' ".$selected.">{$name}</option>";
                }
              }else{
                echo 'No categories yet, you need to add one';
              }
            ?>
          </select>
        </div>
      </div>
      <div class="input-group">
        <label>Short description</label>
        <?php echo '<textarea class="small" placeholder="Describe your product...." name="short_description">'.$product->getShortDescription().'</textarea>'; ?>
      </div>
      <div class="input-group">
        <label>Description</label>
        <?php echo '<textarea placeholder="Describe your product...." name="description">'.$product->getDescription().'</textarea>'; ?>
      </div>
      <div class="tabs" id="variants" style="margin-top: 50px">
          <?php
            $variants = $product->getVariants();
            if (count($variants) > 0){
              foreach($variants as $key => $variant) {
                $count = $key + 1;
                echo '<div class="tab" id="variant'.$count.'-tab" column="variant'.$count.'">Variant '.$count.'</div>';
              }
            } else {
              echo '<div class="tab" id="variant1-tab" column="variant1">Variant 1</div>';
            }

          ?>
          <div class="tab add" id="add-tab"></div>
      </div>
      <div class="variants" id="variants-container">
        <?php
          foreach($variants as $key => $variant) {
            $count = $key + 1;
            echo '<section class="hidden transparent"  id="variant'.$count.'">'; 
          ?>
              <!-- <button class="remove" id="remove-variant">Delete variant</button> -->
              <?php echo '<input type="hidden" name="variants['.$key.'][id]" value="'.$variant->getId().'" />'; ?>
              <div class="input-group">
                <label>Name</label>
                <?php echo '<input type="string" name="variants['.$key.'][variant_name]" placeholder="Product variant" value="'.$variant->getName().'" />'; ?>
              </div>
              <div class="input-group">
                <label>Currency</label>
                  <div class="select-box dark">
                    <?php
                        echo '<select name="variants['.$key.'][currency]" value="'.$variant->getCurrency().'" id="currency">'; 
                        $eur = $variant->getCurrency() == "eur" ? ' selected="selected"' : '';
                        $usd = $variant->getCurrency() == "usd" ? ' selected="selected"' : '';
                        $aud = $variant->getCurrency() == "aud" ? ' selected="selected"' : '';
                        $mxn = $variant->getCurrency() == "mxn" ? ' selected="selected"' : '';
                        echo '<option value="eur" '.$eur.'>EURO</option>'; 
                        echo '<option value="usd" '.$usd.'>USD</option>'; 
                        echo '<option value="aud" '.$aud.'>AUD</option>'; 
                        echo '<option value="mxn" '.$mxn.'>MXN</option>'; 
                      
                      echo '</select>';
                    ?>
                  </div>
              </div>
              <div class="input-group">
                <label>Amount</label>
                <?php echo '<input type="number" step="0.01"  name="variants['.$key.'][amount]" value="'.Shop::intToDoubleSeperator($variant->getPrice(), ".").'" placeholder="20,00" />'; ?>
              </div>
              <div class="input-group">
                <label>Stock quantity</label>
                <?php echo '<input type="number"  name="variants['.$key.'][quantity]" value="'.$variant->getQuantity().'" placeholder="10">'; ?>
              </div>
              <div class="input-group">
                <label>Size (cm)</label>
                <div class="group">
                  <?php echo '<input type="number" name="variants['.$key.'][sizeWidth]" value="'.$variant->getSizeWidth().'" placeholder="width">'; ?>
                  <?php echo '<input type="number" name="variants['.$key.'][sizeHeight]" value="'.$variant->getSizeHeight().'" placeholder="height">'; ?>
                  <?php echo '<input type="number" name="variants['.$key.'][sizeDepth]" value="'.$variant->getSizeDepth().'" placeholder="depth">'; ?>
                </div>
              </div>
              <div class="input-group">
                <label>Weight (Kg)</label>
                <?php echo '<input type="number" name="variants['.$key.'][weight]" value="'.$variant->getWeight().'" placeholder="10">'; ?>
              </div>
              <input type="hidden" name="variables" id="variables"  placeholder="10">
              <div class="input-group">
                <label>Image</label>
                <div class="image-selector" id="image-selector">
                  <?php
                    foreach($variant->getImages() as $imageKey => $image){
                      echo '
                        <span>
                          <span class="delete" onclick="deleteImage(this,\''.$image.'\')"></span>
                          <input type="hidden" name="variants['.$key.'][images][]" value="'.$image.'" /> 
                          <img src="'.$image.'" />
                        </span>
                      ';
                    }
                  ?>
                  <div class="add" onclick="imageEditor()"></div>
                </div>
              </div>
              <div class="input-group">
                <label>Attributes</label>
                <?php echo '<div class="attributes" id="attributes'.$count.'">'; ?>
                  <div class="attribute template" id="attribute-template">
                    <input type="text" class="attribute-name" placeholder="Name"/>
                    <input type="text" class="attribute-value" placeholder="Value"/>
                    <button class="delete" id="delete"></button>
                  </div>
                  <?php 
                    foreach($variant->getAttributes() as $aKey => $attribute) {
                      $aCount = $aKey + 1;
                      echo '
                        <div class="attribute" id="attribute">
                          <input type="text" class="attribute-name" name="variables['.$key.'][attributes]['.$aKey.']]name]" value="'.$attribute->getName().'" placeholder="Name"/>
                          <input type="text" class="attribute-value" name="variables['.$key.'][attributes]['.$aKey.']]value]" value="'.$attribute->getValue().'"placeholder="Value"/>
                          <button class="delete" id="delete"></button>
                        </div>
                      ';
                    }
                  ?>
                  <?php echo '<button class="" id="add-attribute'.$count.'">Add attribute</button>'; ?>
                </div>  
              </div>
        <?php
            echo '</section>';
          }
        ?>
      </div>
      <section class="hidden transparent" id="variant">
        <button class="remove" id="remove-variant">Delete variant</button>
        <div class="input-group">
          <label>Name</label>
          <input type="string" name="variant_name" id="variant_name" placeholder="Product variant" />
        </div>
        <div class="input-group">
          <label>Currency</label>
            <div class="select-box dark">
              <select name="currency" id="currency">
                <option value="eur">EURO</option>
                <option value="eur">USD</option>
                <option value="eur">AUD</option>
                <option value="eur">MXN</option>
              </select>
            </div>
        </div>
        <div class="input-group">
          <label>Amount</label>
          <input type="number" step="0.01" id="amount" name="amount" placeholder="20,00" />
        </div>
        <div class="input-group">
          <label>Stock quantity</label>
          <input type="number" name="quantity" id="quantity" placeholder="10">
        </div>
        <div class="input-group">
          <label>Size (cm)</label>
          <div class="group">
            <input type="number" name="sizeW" id="sizeW" placeholder="width">
            <input type="number" name="sizeH" id="sizeH" placeholder="height">
            <input type="number" name="sizeD" id="sizeD" placeholder="depth">
          </div>
        </div>
        <div class="input-group">
          <label>Weight (Kg)</label>
          <input type="number" name="weight" id="weight" placeholder="10">
        </div>
        <input type="hidden" name="variables" id="variables"  placeholder="10">
        <div class="input-group">
          <label>Image</label>
          <div class="image-selector" id="image-selector">
            <div class="add" onclick="imageEditor()"></div>
          </div>
        </div>
        <div class="input-group">
          <label>Attributes</label>
          <div class="attributes" id="attributes">
            <div class="attribute template" id="attribute-template">
              <input type="text" class="attribute-name" placeholder="Name"/>
              <input type="text" class="attribute-value" placeholder="Value"/>
              <button class="delete" id="delete"></button>
            </div>
            <button class="" id="add-attribute">Add attribute</button>
          </div>  
        </div>
      </section>
      <div class="input-group right">
        <a href="/felta/shop/products"><input type="button" value="Cancel"></a>
        <input type="submit" name="new_news" value="Save product">
      </div>
    </form>
    <div class="main">
      <section class="image_editor" id="image_editor">
        <div class="background" id="image_editor_background"></div>
        <div class="editor">

          <div class="container" id="imageeditor">
            <form method="post" class="select-image" enctype="multipart/form-data">
              <div class="imageid" id="imageid"></div>
              <div class="box__input">
                <svg class="box__icon" width="50" height="43" viewBox="0 0 50 43"><path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z"></path></svg>
                <input class="box__file" type="file" id="file" accept="image/*" />
                <label for="file"><strong>Choose a file</strong>
                  <span class="box__dragndrop"> or drag it here</span>.
                </label>
              </div>
          </form>
        </div>
        <div class="buttons">
          <button id="cancelphoto">Cancel</button>
          <button id="addphoto">Add</button>
        </div>
      </div>
    </section>
  </div>
  <script src="/felta/js/shop.js" type="text/javascript"></script>
  <script src="/felta/js/shop/product.js"></script>
</body>
</html>