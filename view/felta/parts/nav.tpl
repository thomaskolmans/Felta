<script src="felta/js/core.js"></script>
<div class="top">
 <div class="listbutton"></div>
 <button id="website">My website</button>
 <div class="account" id="account">
    <div class="name">Hey, <?php echo $_SESSION['user'][1]; ?></div>
    <menu id="top_menu">
      <a href="/Felta/settings"><li><i class="fa fa-cog"></i>Settings</li></a>
      <a href="/felta/logout"><li><i class="fa fa-sign-out"></i>Logout</li></a>
    </menu>
  </div>
</div>
<div class="left">
 <img src="felta/images/logo_white2.png">
   <ul>
     <a href="/Felta/dashboard"><li><i class="fa fa-home"></i>Dashboard</li></a>
     <a href="/Felta/editor"><li><i class="fa fa-eraser"></i>Edit</li></a>
     <a href="/Felta/agenda"><li><i class="fa fa-calendar"></i>Agenda</li></a>
   </ul>
    <div class="bottom">
      <a class="logout" href="/Felta/logout"><i class="fa fa-sign-out"></i></a>
      <a class="settings" href="/Felta/settings"><i class="fa fa-cog"></i></a>
    </div>
</div>