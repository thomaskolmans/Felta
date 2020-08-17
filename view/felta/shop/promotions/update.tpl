</html>

<head>
  <title>Felta | New promotion</title>
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
    <h1>New promotion</h1>
    <form method="post" class="full" id="new_item" action="/felta/shop/add/item">
      <div class="input-group">
        <label>Name</label>
        <input type="text" name="name" placeholder="Promotion name" />
      </div>
      <div class="input-group">
        <label>Percentage</label>
        <input type="text" name="percentage" placeholder="Percentage" />
      </div>
      <div class="input-group">
        <label>Amount</label>
        <input type="text" name="percentage" placeholder="Percentage" />
      </div>
      <div class="input-group right">
        <a href="/felta/shop/promotions"><input type="button" value="Cancel"></a>
        <input type="submit" name="new_news" value="Save product">
      </div>
    </form>
    <script src="/felta/js/shop.js" type="text/javascript"></script>
</body>

</html>