<html>

<head>
 <title>Felta | Reset</title>
 <link href="/felta/stylesheets/front.css" rel="stylesheet">
 <link rel="icon" href="/felta/images/black.png" type="image/png"></link>
 <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
<?php 
    $id = isset($_GET["code"]) ? $_GET["code"] : null;
    $user = lib\Felta::getInstance()->user;
    if($id !== null){
      if($user->verifyForgot($id)){
?>
      <form method="post">
          <img src="/felta/images/logo_white2.png">
          <div class="input-group">
              <label>New password</label>
              <input type="password" name="password">
          </div>
          <div class="input-group">
              <label>Repeat password</label>
              <input type="password" name="repeat_password">
          </div>

          <input type="submit" name="newpassword" value="reset">
      </form>
<?php
        if(isset($_POST['newpassword'])){
          $password = $_POST['password'];
          $repeatpassword = $_POST['repeat_password'];
          $user->recoverPassword($id,$password,$repeatpassword);
          header("Location: /felta");
        }
      } else {
        echo "Invalid key";
      }

    }else{ 
?>
      <form method="post" class="reset">
        <img src="/felta/images/logo_white2.png">
        <div class="input-group">
          <label>Your username/email</label>
          <input type="text" name="email">
        </div>
        <input type="submit" name="reset" value="recover">
        <a href="/felta" class="center">Back</a>
      </form>
    <?php } 
      if(isset($_POST['reset'])){
        $user->forgot($_POST['email']);
        header('Location: /felta');
      }
    ?>
</body>

</html>
