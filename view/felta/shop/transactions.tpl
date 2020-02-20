<html>
<head>
   <title>Felta | Transactions</title>
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
      <h1>Transactions</h1>
      <section class="full first transaction-list">
        <?php
          use lib\shop\Transaction;
          use lib\shop\Shop;
          
          $from = 0;
          $until = 20;
          if(isset($_GET["from"]) && isset($_GET["until"])){
            $from = $_GET["from"];
            $until = $_GET["until"];
          }
          $transactions = Transaction::getLatest($from, $until);
          if(count($transactions) > 0){
            $count = 0;
            foreach($transactions as $transaction){
              $id = $transaction["id"];
              echo '
              <a href="/felta/shop/transaction/'.$id.'">
                <div class="transaction">
                  <div class="amount">€'.Shop::intToDouble($transaction["amount"]).'</div>
                  <div class="amount">'.(new DateTime($transaction["date"]))->format("d M Y H:i").'</div>
                  <div class="id">'.$id.'</div>
                </div>
              </a>';
            }
          }else{
            echo '<i>No transactions</i>';
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
                      echo '<a href="/felta/shop/transactions/'.$from.'/'.$until.'"><button>Previous</button></a>';
                    }
                    if(count($transactions) >= $until){
                      $until += 20;
                      $from += 20;
                      echo '<a href="/felta/shop/transactions/'.$from.'/'.$until.'"><button>Next</button></a>';
                    }
              echo '</div>';
            }
      ?>
    </div>
  </div>
</body>
</html>