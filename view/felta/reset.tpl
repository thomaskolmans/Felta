<html>

<head>
 <title>Felta System</title>
 <link href="felta/stylesheets/front.css" rel="stylesheet">
 <link rel="icon" href="Felta/images/black.png" type="image/png"></link>
 <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
<?php 
    $id = isset($_GET["code"]) ? $_GET["code"] : null;
    if(isset($id)){ ?>
        <form method="post">
            <img src="Felta/images/logo_white2.png">
            <div class="input-group">
                <label>New password</label>
                <input type="password" name="password">
            </div>
            <div class="input-group">
                <label>Repeat password</label>
                <input type="password" name="repeat_password">
            </div>
            <input type="submit" value="Reset">
        </form>
    <?php }else{ ?>
     <form method="post" class="reset">
      <img src="Felta/images/logo_white2.png">
      <div class="input-group">
       <label>Your username/email</label>
       <input type="text" name="Email">
      </div>
      <input type="submit" value="Recover">
     </form>
    <?php } ?>
</body>

</html>
