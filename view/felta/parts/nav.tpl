<script src="/felta/js/core.js"></script>
<div class="top">
 <div class="listbutton"></div>
 <button id="website">My website</button>
 <div class="account" id="account">
    <div class="name">Hey, <?php echo $_SESSION['user'][1]; ?></div>
    <menu id="top_menu">
      <a href="/felta/settings"><li><i class="fa fa-cog"></i>Settings</li></a>
      <a href="/felta/logout"><li><i class="fa fa-sign-out"></i>Logout</li></a>
    </menu>
  </div>
</div>
<div class="left">
 <img src="/felta/images/logo_white2.png">
   <ul>
     <a href="/felta/dashboard"><li><i class="fa fa-home"></i>Dashboard</li></a>
     <a href="/felta/editor"><li><i class="fa fa-edit"></i>Edit</li></a>
     <!-- <a href="/felta/agenda"><li><i class="fa fa-calendar"></i>Agenda</li></a> -->
     <!--
     <a href="/felta/news"><li><i class="fa fa-newspaper"></i>News</li></a>
     <a href="/felta/social"><li><i class="fa fa-users"></i>Social</li></a>
     <a href="/felta/audio"><li><i class="fa fa-volume-up"></i>Audio</li></a> -->
     <a href="/felta/blog"><li><i class="fa fa-pencil"></i>Blog</li></a>
     <!-- 
     <a href="/felta/faq"><li><i class="fa fa-pencil"></i>FAQ</li></a>
     -->
     <a href="/felta/shop">
      <li>
        <i class="fa fa-shopping-cart"></i>
        Shop
        <ul>
          <a href="/felta/shop/orders/0/20"><li>Orders</li></a>
          <a href="/felta/shop/products/0/20"><li>Products</li></a>
          <a href="/felta/shop/transactions/0/20"><li>Transactions</li></a>
          <a href="/felta/shop/customers/0/20"><li>Customers</li></a>
          <a href="/felta/shop/discounts/0/20"><li>Discounts</li></a>
          <a href="/felta/shop/categories"><li>Categories</li></a>
        </ul>
      </li>
     </a>
   </ul>
    <div class="bottom">
      <a class="logout" href="/felta/logout"><i class="fa fa-sign-out"></i></a>
      <a class="settings" href="/felta/settings"><i class="fa fa-cog"></i></a>
    </div>
</div>