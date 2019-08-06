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
    use lib\Shop\Order\Order;
    use lib\Shop\Shop;
    use lib\Felta;
  ?>
  <include>felta/parts/nav.tpl</include>
  <div class="main-wrapper">
    <div class="main dashboard flex">
      <h1>Shop</h1>
      <div class="flex">
          <section class="full relative overview">
            <a href="/felta/shop/settings"><button class="add">Settings</button></a>
            <h2 class="align-left">This months total: €<?php echo Shop::intToDouble(Felta::getInstance()->getSQL()->execute("SELECT SUM(amount) AS total FROM `shop_transaction` where year(date) = year(curdate())")[0]["total"]); ?></h2>
            <h2 class="align-left">Total: €<?php echo Shop::intToDouble(Felta::getInstance()->getSQL()->execute("SELECT SUM(amount) AS total FROM `shop_transaction`")[0]["total"]); ?></h2>
            <canvas id="buystats"></canvas>
          </section>
        </div>
      </div>
    </div>
    <script>
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