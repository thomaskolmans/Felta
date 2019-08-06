<html>
<head>
   <title>Felta | Shop categories</title>
   <link href="/felta/stylesheets/main.css" rel="stylesheet">
   <link href="/felta/js/quill/quill.snow.css" rel="stylesheet">
   <link rel="icon" href="/felta/images/black.png" type="image/png" />
   <link rel="stylesheet" href="/felta/fonts/font-awesome.min.css" />
   <link rel="stylesheet" href="/felta/fonts/font-awesome.css" />
   <script src="/felta/js/jquery-1.11.3.min.js"></script>
   <script src="/felta/js/Chart.min.js"></script>
   <script src="/felta/js/shop.js"></script>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <?php
    use lib\Shop\Transaction;
    use lib\Shop\Order\Order;
    use lib\Shop\Shop;
    use lib\Felta;
  ?>
  <include>felta/parts/nav.tpl</include>
  <div class="main-wrapper">
    <div class="main dashboard container">
        <h1> Categories </h1>
        <div class="lang_container">
            <?php
            $catagories = Shop::getInstance()->getCatagories();
            if($catagories && count($catagories) > 0){
                foreach($catagories as $key => $catagory){
                $name = $catagory["name"];
                $id = $catagory["id"];
                echo "<div class='lang_container'><div class='language'>{$name}<div class='remove' catagory-id='{$id}'></div></div></div>";
                }
            }else{
                echo '<h3>No catagories, add one to get started.</h3>';
            }
            ?>
        </div>
        <form method="post" class="new-language" action="/felta/shop/add/catagory" style="display: none;">
            <input type="text" name="catagory">
            <input type="submit" value="add" name="addcatagory">
        </form>
        <div class="new" id="new_catagory"></div>
        </section>
      </div>
    </div>
    <script>
        $(".new-language").hide();
        $("#new_catagory").on('click',function(){
            $(".new-language").fadeIn();
        });
        $('.remove').on('click',function(){
            var catagoryid = $(this).attr("catagory-id");
            $(this).parent().remove();
            $.ajax({
                url: "/felta/shop/delete/catagory",
                type: "POST",
                data: {
                'catagory': catagoryid
                }
            });
        });
      </script>
</body>
</html>