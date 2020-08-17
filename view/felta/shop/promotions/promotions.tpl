<html>
<head>
   <title>Felta | Shop products</title>
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
    use lib\shop\Promotion;
    use lib\shop\Shop;
    use lib\Felta;
  ?>
  <include>felta/parts/nav.tpl</include>
  <div class="main-wrapper">
    <div class="main dashboard container">
      <h1>Promotions</h1>
      <section class="stats no-margin agenda relative">
        <a href="/felta/shop/add/promotion"><button class="add">Add promotion</button></a>
        <table>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Starts At</th>
            <th>Ends At</th>
            <th class="clear"></th>
            <th class="clear"></th>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <?php
              $from = 0;
              $until = 20;
              if(isset($_GET["from"]) && isset($_GET["until"])){
                $from = $_GET["from"];
                $until = $_GET["until"];
              }
              $promotions = Promotion::all($from, $until);
              if($promotions != null){
                foreach($promotions as $promotion){
                  echo "
                  <tr>
                    <td class='align-left'>{$promotion['id']}</td>
                    <td class='align-left'>{$promotion['name']}</td>
                    <td>{$product['category']}</td>
                    <td><a href='/felta/shop/update/promotion/".$promotion["id"]."'><button>Edit</button></a></td>
                    <td><a href='/felta/shop/delete/promotion/".$promotion["id"]."'><div class='delete'></div></a></td>
                  </tr>
                  ";
                }
              } else {
                echo "<tr> <td colspan='5'><i>No promotions</i></td></tr>";
              }
          ?>
        </table>
      </section>
      <?php
            if(count($promotions) >= $until || $from > 0){
              echo '<div class="buttons">';
                    if($from > 0){
                      if($from < 20){
                        $from = 0;
                        $until = 20;
                      }
                      echo '<a href="/felta/shop/promotion/'.$from.'/'.$until.'"><button>Previous</button></a>';
                    }
                    if(count($promotions) >= $until){
                      $until += 20;
                      $from += 20;
                      echo '<a href="/felta/shop/promotion/'.$from.'/'.$until.'"><button>Next</button></a>';
                    }
              echo '</div>';
            }
      ?>
      </div>
    </div>
</body>
</html>