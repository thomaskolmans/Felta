</html>

<head>
  <title>Felta | New Item</title>
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
    <h1>New Item</h1>
    <form method="post" class="news" id="new_item" action="/felta/shop/add/item">
      <div class="input-group">
        <label>Title</label>
        <input type="text" name="name" placeholder="A product name" />
      </div>
      <div class="input-group">
        <label>Category</label>
        <div class="select-box dark">
          <select name="catagory">
            <option disabled selected value="">-- select a category --</option>
            <?php

            use lib\Shop\Shop;

            $shop = Shop::getInstance();
            $catagories = $shop->getCategories();

            if ($catagories === null) {
              $catagories = [];
            }
            if (count($catagories) > 0) {
              foreach ($catagories as $key => $catagory) {
                $name = $catagory["name"];
                echo "<option value='{$name}'>{$name}</option>";
              }
            } else {
              echo 'No catagories yet, you need to add one';
            }
            ?>
          </select>
        </div>
      </div>
      <div class="input-group">
        <label>Description</label>
        <textarea placeholder="Describe your product...." name="description"></textarea>
      </div>
      <input type="hidden" id="variants" />
      <div class="tabs" style="margin-top: 50px">
        <div class="tab" id="variant1-tab" column="variant1">Variant 1</div>
        <div class="tab add" id="add-tab"></div>
      </div>
      <section class="hidden transparent" id="variant1">
        <div class="input-group">
          <label>Currency</label>
          <div class="select-box dark">
            <select name="currency1">
              <option value="eur">EURO</option>
              <option value="eur">USD</option>
              <option value="eur">AUD</option>
              <option value="eur">MXN</option>
            </select>
          </div>
        </div>
        <div class="input-group">
          <label>Amount</label>
          <input type="number" step="0.01" id="amount" name="amount1" placeholder="20,00" />
        </div>
        <div class="input-group">
          <label>Quantity</label>
          <input type="number" name="quantity1" id="quantity1" placeholder="10">
        </div>
        <input type="hidden" name="variables1" id="variables1" placeholder="10">
        <div class="input-group">
          <label>Image</label>
          <div class="image-selector" id="image-selector">
            <div class="add" onclick="imageEditor()"></div>
          </div>
        </div>
      </section>
      <div class="input-group right">
        <a href="/felta/shop"><input type="button" value="Cancel" id="cancel_news"></a>
        <input type="submit" name="new_news" value="Add item">
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
                <svg class="box__icon" width="50" height="43" viewBox="0 0 50 43">
                  <path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z"></path>
                </svg>
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
    <script>
      var variants = 1;
      var images = 0;
      var active = "variant1";
      var last = "variant1";
      $("#" + active).removeClass("hidden");
      $("#" + active + "-tab").addClass("active");
      $(".tab").on("click", function(e) {
        last = active;
        $("#" + last).addClass("hidden");
        $("#" + last + "-tab").removeClass("active");
        active = $(this).attr("column");
        $("#" + active).removeClass("hidden");
        $("#" + active + "-tab").addClass("active");
      });
      $("#image_edit_cancel, #image_editor_background").click(function() {
        closeImageEditor();
      });
      $("#cancelphoto").on("click", function() {
        closeImageEditor();
      });
      $("#addphoto").on("click", function() {
        $('#imageid').croppie('result', {
          type: 'canvas',
          size: 'viewport'
        }).then(function(resp) {
          uploadImage(
            base64ToBlob(
              resp.replace(/^data:image\/(png|jpg);base64,/, ""), 'image/png')
          ).then((response) => {
            json = JSON.parse(response);

            $("#image-selector").prepend("<span image-id='" + json.uid + "'><span class='delete' onclick='deleteImage(this,\"" + json.url + "\")' /><input type='hidden' name='images[]' value='" + json.url + "' /> <img src='" + json.url + "' /></span>");
            closeImageEditor();
            images++;
          });
        });
      });
      document.getElementById("amount").onblur = function() {
        this.value = parseFloat(this.value.replace(/,/g, ""))
          .toFixed(2)
          .toString()
          .replace(/\B(?=(\d{3})+(?!\d))/g, "");
      }

      function deleteImage(e, url) {
        $.ajax({
          url: "/felta/shop/delete/image",
          type: "POST",
          data: {
            url: url
          },
          success: function(response) {}
        });
        $(e).parent().remove();
      }

      function addVariant() {
        variants++;
      }
    </script>
</body>

</html>