</html>
<head>
   <title>Felta | Blog</title>
   <link href="/felta/stylesheets/main.css" rel="stylesheet">
   <link href="/felta/js/quill/quill.snow.css" rel="stylesheet">
   <link rel="icon" href="/felta/images/black.png" type="image/png" />
   <link rel="stylesheet" href="/felta/fonts/font-awesome.min.css" />
   <link rel="stylesheet" href="/felta/fonts/font-awesome.css" />
   <script src="felta/js/jquery-1.11.3.min.js"></script>
   <script src="felta/js/dash.js"></script>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <script type="text/javascript">
        $(document).ready(function(){
            var active = "general";
            var last = "general";
            $("#"+active).removeClass("hidden");
            $("#"+active+"-tab").addClass("active");
            $(".tab").on("click",function(e){
                last = active;
                $("#"+last).addClass("hidden");
                $("#"+last+"-tab").removeClass("active");
                active = $(this).attr("column");
                $("#"+active).removeClass("hidden");
                $("#"+active+"-tab").addClass("active");
            });
        });
    </script>
</head>
<body>
   <include>felta/parts/nav.tpl</include>
   <div class="main">
      <div class="tabs">
          <div class="tab" id="general-tab" column="general">General</div>
          <div class="tab" id="social-tab" column="users">Social</div>
          <div class="tab" id="acc-tab" column="acc">Account</div>
      </div>
    </div>
</body>