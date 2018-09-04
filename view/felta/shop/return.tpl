<html>
<head>
   <title>Felta | Loading ....</title>
   <link href="/felta/stylesheets/main.css" rel="stylesheet">
   <link href="/felta/js/quill/quill.snow.css" rel="stylesheet">
   <link rel="icon" href="/felta/images/black.png" type="image/png" />
   <link rel="stylesheet" href="/felta/fonts/font-awesome.min.css" />
   <link rel="stylesheet" href="/felta/fonts/font-awesome.css" />
   <script src="/felta/js/jquery-1.11.3.min.js"></script>
   <script src="/felta/js/Chart.min.js"></script>
   <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
   <div class="spinner"></div>
   <script src="/felta/js/shop.js"></script>
   <script type="text/javascript">
      var url_string = window.location.href;
      var url = new URL(url_string);
      var returnsrc = this.url.searchParams.get("source");
      if(returnsrc !== null){
         stripe.retrieveSource({
            id: returnsrc,
            client_secret: url.searchParams.get("client_secret")
         }).then(function(result) {
            console.log(result.source.status);
            if(result.source.status === "chargeable"){
               createChargeFromSource(result.source).then(function(){
                  window.location = "/felta/shop/thankyou";  
               });
            }else if(result.source.status === "consumed"){
               window.location = "/felta/shop/thankyou";  
            }else{
               window.location = "/felta/shop/error";
            }
         });
      }else{
         window.location = "/felta/shop/error";
      }
   </script>
</body>
</html>