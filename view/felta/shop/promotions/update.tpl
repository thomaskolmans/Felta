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
  <script src="/felta/js/datepicker/jquery.datetimepicker.min.js" type="text/javascript"></script>
  <script src="/felta/js/croppie/croppie.min.js" type="text/javascript"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
  <include>felta/parts/nav.tpl</include>
  <?php

  use lib\shop\Promotion;

  $promotion;
  if (isset($_GET["id"])) {
    $itemid = $_GET["id"];
    if (Promotion::exists($itemid)) {
      $promotion = Promotion::get($itemid);
    } else {
      echo "<h1>Product does not exist</h1>";
      exit;
    }
  }
  ?>
  <div class="main dashboard container" id="main">
    <h1>New promotion</h1>
    <form method="post" class="full" id="new_item" action="/felta/shop/update/promotion">
    <?php echo '<input type="text" name="id" hidden value="' . $promotion->getId() . '"/>'; ?>
      <div class="input-group">
        <label>Name</label>
        <?php echo '<input type="text" name="name" placeholder="Promotion name" value="' . $promotion->getName() . '"/>'; ?>
      </div>
      <div class="input-group">
        <label>Percentage</label>
        <?php echo '<input type="text" name="percentage" placeholder="Percentage" value="' . $promotion->getPercentage() . '"/>'; ?>
      </div>
      <div class="input-group">
        <label>Amount</label>
        <?php echo '<input type="text" name="amount" placeholder="Amount (cents)" value="' . $promotion->getAmount() . '"/>'; ?>
      </div>
      <div class="input-group">
        <label>Starts at</label>
        <?php echo '<input type="text" id="startsAt"  name="startsAt" value="' . $promotion->getStartsAt()->format("d-m-Y H:i:s") . '"/>'; ?>
      </div>
      <div class="input-group">
        <label>Ends at</label>
        <?php echo '<input type="text" id="endsAt" name="endsAt" value="' . $promotion->getEndsAt()->format("d-m-Y H:i:s") . '"/>';  ?>
      </div>
      <div class="input-group right">
        <a href="/felta/shop/promotions"><input type="button" value="Cancel"></a>
        <input type="submit" name="new_news" value="Update promotion">
      </div>
    </form>
    <script src="/felta/js/shop.js" type="text/javascript"></script>
    <script>
      jQuery('#startsAt').datetimepicker({
        format: 'd.m.Y H:i',
        inline: true,
        lang: 'en',
        startDate: new Date()
      });
      jQuery('#endsAt').datetimepicker({
        format: 'd.m.Y H:i',
        inline: true,
        lang: 'en',
        startDate: new Date()
      });
    </script>
</body>

</html>