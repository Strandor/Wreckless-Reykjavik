<?php
require_once('../includes/main.php');

if(!isset($_SESSION['finish'])) {
    error('NOT REDIRECTED', 'Sorry you have to be redirected by our server to access this page');
}

mail($_SESSION['email'], "Your order has been confirmed", "Thank you for your order. Here are some info for you.<br> Order id:" .  $_SESSION['finish'] . '<br><br>Need help? Contact support: support@' . $_SERVER['SERVER_NAME'], $_SERVER['SERVER_NAME'] . '/account', "Click to view profile");


$_SESSION['items'] = null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Finished - Wreckless Reykjavík</title>
    <?php
    include("../includes/html/header.html");
    ?>
</head>
<body>
<?php
include("../includes/html/navigation.php");
?>
<div class="full-page">
    <?php
        createTitle('FINISHED');
        info('ORDER PLACED', 'Your order has been placed, thank you!<br>Order id: #' . $_SESSION['finish']);
    ?>
</div>
<?php
include("../includes/html/footer.html");
$_SESSION['finish'] = null;
?>

</body>
</html>