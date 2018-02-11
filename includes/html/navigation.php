<?php
$cookieWarning = false;
if(!isset($_SESSION['cookies'])) {
    echo '<div class="banner"><p>By using this site you agree to our use of cookies. <a href="/legal/privacy">privacy</a></p></div>';
    echo '<style>body {padding-top: 30px;}</style>';
    $_SESSION['cookies'] = true;
    $cookieWarning = true;
}
?>
<div class="menu">
  <img id="open" src="/assets/vector/menu.svg" alt="menu open" onclick="javascript:openNav();"/>
   <div class="right">
     <a href="/account/login"><img src="/assets/vector/account.svg" alt="account open"/></a>
     <a href="/checkout"><img src="/assets/vector/cart.svg" alt="checkout open"/></a>
   </div>
</div>
<div id="sideNav" class="sideNav">
    <img id="close" onclick="closeNav();" src="/assets/vector/close.svg" alt="menu close"/>
    <div class="centered">
        <h1><a href="/">HOME</a></h1>
        <h1><a href="/items">ITEMS</a></h1>
        <h1><a href="/account/profile">ACCOUNT</a></h1>
        <h1><a href="/legal/privacy">PRIVACY</a></h1>
        <h1><a href="/legal/tos">TOS</a></h1>
    </div>
</div>
