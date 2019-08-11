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
    use lib\shop\Shop;
    use lib\Felta;
  ?>
  <include>felta/parts/nav.tpl</include>
  <div class="main-wrapper">
    <div class="main dashboard container languages">
        <h1> Categories </h1>
        <div class="lang_container">
            <?php
            $categories = Shop::getInstance()->getCategories();
            if($categories && count($categories) > 0){
                foreach($categories as $key => $category){
                    $name = $category["name"];
                    $id = $category["id"];
                    echo "
                        <div class='lang_container'>
                            <div class='language'>{$name}<div class='remove' category-id='{$id}'></div></div>
                        </div>";
                }
            }else{
                echo '<h3>No categories, add one to get started.</h3>';
            }
            ?>
        </div>
        <form method="post" class="new-language" action="/felta/shop/add/category" style="display: none;">
            <input type="text" name="category">
            <input type="submit" value="add" name="addcategory">
        </form>
        <div class="new" id="new_category"></div>
        </section>
      </div>
    </div>
    <script>
        $(".new-language").hide();
        $("#new_category").on('click',function(){
            $(".new-language").fadeIn();
        });
        $('.remove').on('click',function(){
            var categoryid = $(this).attr("cataeory-id");
            $(this).parent().remove();
            $.ajax({
                url: "/felta/shop/delete/category",
                type: "POST",
                data: {
                'category': categoryid
                }
            });
        });
      </script>
</body>
</html>