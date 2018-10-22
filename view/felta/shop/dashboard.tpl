<html>
<head>
   <title>Felta | Shop</title>
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
    use lib\Shop\Order;
    use lib\Shop\Shop;
    use lib\Felta;
  ?>
  <include>felta/parts/nav.tpl</include>
  <div class="main dashboard flex">
    <h1>Shop</h1>
    <div class="flex">
        <section class="full relative overview">
          <a href="/felta/shop/settings"><button class="add">Settings</button></a>
          <h1>Overview</h1>
          <h2 class="align-left">This months total: €<?php echo Shop::intToDouble(Felta::getInstance()->getSQL()->execute("SELECT SUM(amount) AS total FROM `shop_transaction` where year(date) = year(curdate())")[0]["total"]); ?></h2>
          <h2 class="align-left">Total: €<?php echo Shop::intToDouble(Felta::getInstance()->getSQL()->execute("SELECT SUM(amount) AS total FROM `shop_transaction`")[0]["total"]); ?></h2>
          <canvas id="buystats"></canvas>
        </section>
        <section class="half order-list">
          <h1>Orders</h1>
          <?php

            $orders = Order::getLatest(0,10);
            if(count($orders) > 0){
              $count = 0;
              foreach($orders as $order){
                $id = $order["id"];
                $o = Order::get($id);
                $date = new \DateTime($order["order"]);
                echo '
                <a href="/felta/shop/order/'.$id.'">
                  <div class="order"> 
                    <div class="date">'.$date->format("d M Y H:i").'</div>
                    <div class="amount">€'.Shop::intToDouble($o->getTotalAmount()).'</div>
                    <div class="id">'.$id.'</div>
                  </div>
                </a>';
                $count++;
                if($count > 7){
                  echo '<a href="/felta/shop/orders/0/20"><div class="more"></div></a>';
                  break;
                }
              }
            }else{
              echo '<h3>No orders have been placed</h3>';
            }
          ?>
        </section>
        <section class="half transaction-list">
          <h1>Transactions</h1>
          <?php
            $transactions = Transaction::getLatest(0,10);
            if(count($transactions) > 0){
              $count = 0;
              foreach($transactions as $transaction){
                $id = $transaction["id"];
                echo '
                <a href="/felta/shop/transaction/'.$id.'">
                  <div class="transaction">
                    <div class="amount">€'.Shop::intToDouble($transaction["amount"]).'</div>
                    <div class="id">'.$id.'</div>
                  </div>
                </a>';
                $count++;
                if($count > 7){
                  echo '<a href="/felta/shop/transactions/0/20"><div class="more"></div></a>';
                  break;
                }
              }
            }else{
              echo '<h3>No transactions made</h3>';
            }
          ?>
        </section>
        <section class="half">
          <h1>Promotions</h1>
          <h3>Not yet available</h3>
        </section>
        <section class="half languages">
          <h1> Catagories </h1>
          <div class="lang_container">
            <?php
              $catagories = Shop::getInstance()->getCatagories();
              if(count($catagories) > 0){
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
        <section class="full more agenda stats relative">
          <a href="/felta/shop/add/item"><button class="add">Add item</button></a>
          <h1> Shop items </h1>
          <table>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Catagory</th>
              <th class="clear"></th>
              <th class="clear"></th>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <?php
                $items = Shop::getInstance()->getItems();
                if($items != null){
                  foreach($items as $item){
                    echo "
                    <tr>
                      <td class='align-left'>{$item['id']}</td>
                      <td class='align-left'>{$item['name']}</td>
                      <td>{$item['catagory']}</td>
                      <td><a href='/felta/shop/update/item/".$item["id"]."'><button>Edit</button></a></td>
                      <td><a href='/felta/shop/delete/item/".$item["id"]."'><div class='delete'></div></a></td>
                    </tr>
                    ";
                  }
                }else{
                  echo "<tr> <td colspan='5'><h3>No shop items</h3></td></tr>";
                }
            ?>
          </table>
        </section>
      </div>
    </div>
    <script >
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
      var dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

      var ONE_DAY = 24 * 60 * 60 * 1000;
      var days = 7;
      var date = new Date();
      var date2 = new Date(date.getTime() - (ONE_DAY * days));

      $.ajax({
          url: "/felta/shop/transactions/week",
          type: "GET",
          success: function(response){
            var values = JSON.parse(response);
            var dataset = [];
            var count = 0;
            var labels = [];
            for(var i = 0; i <= days; i++) {
                var d = new Date(date2.getTime() + ONE_DAY * i);
                labels.push(dayNames[d.getDay()]);
                if (values.length > count){
                  if(sameDay(d, new Date(values[count][1]))){
                    dataset[i] = intToDouble(parseInt(values[count][0]));
                    count++;
                  } else {
                    dataset[i] = 0;
                  }
                } else {
                    dataset[i] = 0;
                }
            }
            var stats = document.getElementById("buystats");
            stats.height = 350;
            var visitors = new Chart(stats,{
                type: 'line',
                data:{
                    labels: labels,
                    datasets:[{
                        borderColor: "#2196F3",
                        backgroundColor: "#2196F3",
                        pointBackgroundColor: "#f1f1f1",
                        label: "Transactions",
                        data: dataset
                    }]
                },
                options: { 
                    legend: {
                        labels: {
                            fontColor: "#f1f1f1",
                        }
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                fontColor: "#f1f1f1",
                                beginAtZero: true
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                fontColor: "#f1f1f1",
                            }
                        }]
                    }
                }
            });
          }
      });
      function sameDay(d1, d2) {
        return d1.getFullYear() === d2.getFullYear() &&
          d1.getMonth() === d2.getMonth() &&
          d1.getDate() === d2.getDate();
      }
      function intToDouble(int){
          return (int.toFixed(2) / 100.00).toFixed(2);
      }
    </script>
</body>
</html>