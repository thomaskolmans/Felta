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
    use lib\Shop\Transaction;
    use lib\Shop\Order\Order;
    use lib\Shop\Shop;
    use lib\Felta;
  ?>
  <include>felta/parts/nav.tpl</include>
  <div class="main-wrapper">
    <div class="main dashboard container">
      <h1>Products</h1>
      <section class="stats no-margin agenda relative">
        <a href="/felta/shop/add/item"><button class="add">Add item</button></a>
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
              } else {
                echo "<tr> <td colspan='5'><i>No products</i></td></tr>";
              }
          ?>
        </table>
      </section>
      </div>
    </div>
</body>
</html>