<html>

<head>
  <title>Felta | Customer</title>
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
    <div class="main dashboard container">
      <h1>Customers</h1>
      <section class="stats no-margin agenda relative">
        <table>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Orders</th>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <?php
          $from = 0;
          $until = 20;
          if (isset($_GET["from"]) && isset($_GET["until"])) {
            $from = $_GET["from"];
            $until = $_GET["until"];
          }
          $customers = Shop::getInstance()->getItems($from, $until);
          if ($customers != null) {
            foreach ($customers as $customer) {
              echo "
                  <tr>
                    <td class='align-left'>{$customer['id']}</td>
                    <td class='align-left'>{$customer['name']}</td>
                    <td>{$customer['category']}</td>
                  </tr>
                  ";
            }
          } else {
            echo "<tr> <td colspan='5'><i>No customer</i></td></tr>";
          }
          ?>
        </table>
      </section>
      <?php
      if (count($customers) >= $until || $from > 0) {
        echo '<div class="buttons">';
        if ($from > 0) {
          if ($from < 20) {
            $from = 0;
            $until = 20;
          }
          echo '<a href="/felta/shop/customers/' . $from . '/' . $until . '"><button>Previous</button></a>';
        }
        if (count($customers) >= $until) {
          $until += 20;
          $from += 20;
          echo '<a href="/felta/shop/customers/' . $from . '/' . $until . '"><button>Next</button></a>';
        }
        echo '</div>';
      }
      ?>
    </div>
  </div>
</body>

</html>