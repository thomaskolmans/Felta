<html>
<head>
   <title>Felta | Orders</title>
   <link href="/felta/stylesheets/main.css" rel="stylesheet">
   <link href="/felta/js/quill/quill.snow.css" rel="stylesheet">
   <link rel="icon" href="/felta/images/black.png" type="image/png" />
   <link rel="stylesheet" href="/felta/fonts/font-awesome.min.css" />
   <link rel="stylesheet" href="/felta/fonts/font-awesome.css" />
   <script src="/felta/js/jquery-1.11.3.min.js"></script>
   <script src="/felta/js/Chart.min.js"></script>
   <script src="/felta/js/shop.js"></script>
   <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <include>felta/parts/nav.tpl</include>
  <div class="main-wrapper">
    <div class="main dashboard">
      <h1>Orders</h1>
      <section class="full first transaction-list">
        <?php
          use lib\shop\order\Order;
          use lib\shop\Shop;

          $from = 0;
          $until = 20;
          if(isset($_GET["from"]) && isset($_GET["until"])){
            $from = $_GET["from"];
            $until = $_GET["until"];
          }
          $transactions = Order::getLatest($from,$until);
          if(count($transactions) > 0){
            $count = 0;
            foreach($transactions as $transaction){
              $id = $transaction["id"];
              $o = Order::get($id);
              $date = new DateTime($transaction["order"]);
              echo '
              <a href="/felta/shop/order/'.$id.'">
                <div class="order"> 
                  <div class="date">'.$date->format("d M Y H:i").'</div>
                  <div class="amount">€'.Shop::intToDouble($o->getTotalAmount()).'</div>
                  <div class="id">'.$id.'</div>
                </div>
              </a>';
            }
          }else{
            echo '<h3>No orders placed yet</h3>';
          }
        ?>
      </section>
      <?php
            if(count($transactions) >= $until || $from > 0){
              echo '<div class="buttons">';
                    if($from > 0){
                      if($from < 20){
                        $from = 0;
                        $until = 20;
                      }
                      echo '<a href="/felta/shop/orders/'.$from.'/'.$until.'"><button>Previous</button></a>';
                    }
                    if(count($transactions) >= $until){
                      $until += 20;
                      $from += 20;
                      echo '<a href="/felta/shop/orders/'.$from.'/'.$until.'"><button>Next</button></a>';
                    }
              echo '</div>';
            }
      ?>
    </div>
  </div>
</body>
</html>