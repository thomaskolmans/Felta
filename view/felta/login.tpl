<html>
    <head>
        <title>Felta | Login</title>
        <link href="/felta/stylesheets/front.css"  rel="stylesheet">
        <link rel="icon" href="/felta/images/black.png" type="image/png"></link>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="/felta/js/jquery-1.11.3.min.js"></script>
        <script src="/felta/js/login.js"></script>
    </head>
    <body>
        <form method="post" class="login" id="login">
            <img src="/felta/images/logo_white2.png">
            <div class="input-group">
               <label>Username/Email</label>
               <input type="text" name="username" id="login_username">
            </div>
            <div class="input-group">
               <label>Password</label>
               <input type="password" name="password" id="login_password">
            </div>
            <div class="input-group">    
               <label class="remember" for="remember" >Remember me</label>
               <input class="remember" type="checkbox" id="remember" name="remember">
            </div>
             <a href="/felta/forgot">Forgot your password?</a>
             <input type="submit" name="login" value="login">
        </form>
    </body>
</html>