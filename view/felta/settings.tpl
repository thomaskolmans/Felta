</html>
<head>
   <title>Felta | Settings</title>
   <link href="felta/stylesheets/main.css" rel="stylesheet">
   <link href="felta/js/quill/quill.snow.css" rel="stylesheet">
   <link rel="icon" href="felta/images/black.png" type="image/png" />
   <link rel="stylesheet" href="felta/fonts/font-awesome.min.css" />
   <link rel="stylesheet" href="felta/fonts/font-awesome.css" />
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
            $("a[href^='#']").on("click",function(e){
                e.preventDefault();
                last = active;
                $("#"+last+"-tab").removeClass("active");
                $("#"+last).addClass("hidden");
                active = $(this).attr("column");
                $("#"+active).removeClass("hidden");
            });
            $("#user-add").on("submit",function(e){
                e.preventDefault();
                $.ajax({
                  url: "/Felta/settings",
                  type: "POST",
                  data: {'addition': "true", 'username': $("#user-username").val(), 'email': $("#user-email").val()},
                  success: function(data){
                    $('#user-add').append(data.trim());
                  }
                });
                $(this).trigger("reset");
            });
            $("#changepassword").on("submit",function(e){
                e.preventDefault();
                $.ajax({
                  url: "/Felta/settings",
                  type: "POST",
                  data: {'changepassword': "true", 'old_password': $("#old_password").val(), 'new_password': $("#new_password").val(),'repeat_new_password':$('#repeat_new_password').val()},
                  success: function(data){
                    $('#changepassword').append(data.trim());
                  }
                });
                $(this).trigger("reset");
            })
            $(".delete-user-button").on("click",function(e){
              e.preventDefault();
              var id = $(this).attr('user-id');
              $.ajax({
                url: "/Felta/settings/delete/user",
                type: "POST",
                data: {id: id}
              });
              $(this).parent().parent().remove();
            });
            $("#general").on("submit",function(e){
              e.preventDefault();
              $.ajax({
                url: "/Felta/settings",
                type: "POST",
                data: {'general': 'true','website_url': $("#website_url").val(),'website_name': $("#website_name").val(),'default_dir': $("#default_dir").val()}
              });
              $(this).append("Changes have been saved!");
            });
        });
    </script>
</head>
<body>
   <include>felta/parts/nav.tpl</include>
   <div class="main settings">
      <h1> Settings</h1>
      <div class="tabs">
          <div class="tab" id="general-tab" column="general">General</div>
          <div class="tab" id="users-tab" column="users">Users</div>
          <div class="tab" id="acc-tab" column="acc">Account</div>
      </div>
      <section class="hidden" id="general">
          <?php
            $felta = lib\Felta::getInstance();
            $settings= $felta->settings;
          ?>
          <form method="post" id="general">
            <div class="input-group">
              <label>Website URL</label>
              <?php echo '<input type="text" name="website_url" id="website_url" value="'.$settings->get('website_url').'" />'; ?>
            </div>
            <div class="input-group">
              <label>Website name</label>
              <?php echo '<input type="text" name="website_name" id="website_name" value="'.$settings->get('website_name').'" />'; ?>
            </div>
            <div class="input-group">
              <label>Default directory</label>
              <?php echo '<input type="text" name="default_dir" id="default_dir" value="'.$settings->get('default_dir').'"/>'; ?>
            </div>
            <div class='input-group right'>
              <input type="submit" name="add-user" value="Save"/>
            </div> 
          </form>
      </section>
      <section class="hidden no-padding" id="users">
          <a href="#add-user" column="add-user"><button class="add">Add user</button></a>
            <?php
                $user =  lib\Felta::getInstance()->user;
                $users = $user->getAll();
                $id = 0;
                foreach($users as $part){  
                  echo '
                  <div class="user">
                    <div class="id">'.$part['id'].'</div>
                    <div class="name">'.$part['username'].'</div>
                    <div class="email">'.$part['email'].'</div>
                    ';
                    if($id > 0){
                      echo '<div class="delete"><button class="delete-user-button" user-id="'.$part['id'].'"></button></div>';
                    }else{
                      echo '<div class="delete"></div>';
                    }
                  echo '</div>';
                    $id++;
                }
            ?>
      </section>
      <section class="hidden no-padding" id="add-user">
        <form method="post" id="user-add">
          <h1>New user</h1>
          <div class="input-group">
            <label> Username </label>
            <input id="user-username" type="text" name="username">
          </div>
          <div class="input-group">
            <label> Email </label>
            <input id="user-email" type="text" name="email">
          </div>
          <div class='input-group right'>
            <input type="submit" name="add-user" value="add"/>
          </div>
        </form>
      </section>
      <section class="hidden" id="acc">
          <?php
            $user =  $_SESSION["user"];
            $id = $user[0];
            $username = $user[1];
            $email = $user[2];
            echo '<div class="me">
                    <div class="line"><label>Username: </label> '.$username.'</div>
                    <div class="line"><label>Email: </label>'.$email.'</div>
                  </div>';
          ?>
          <hr>
          <form method="post" id="changepassword">
            <h1>Change password</h1>
            <div class="input-group">
              <label>Old password</label>
              <input type="password" name="old_password" id="old_password">
            </div>
            <div class="break"></div>
            <div class="input-group">
              <label>New password</label>
              <input type="password" name="new_password" id="new_password">
            </div>
            <div class="input-group">
              <label>Repeat password</label>
              <input type="password" name="repeat_new_password" id="repeat_new_password">
            </div>
            <div class="input-group right">
              <input type="submit" name="changepassword" value="Change">
            </div>
          </form>
      </section>
   </div>
</body>
</html>