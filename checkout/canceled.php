<?php
require_once('../includes/main.php');

if(!isset($_SESSION['ORDERID'])) {
    error('NOT REDIRECTED', 'Sorry you have to be redirected by our server to access this page');
}
$sql = 'DELETE FROM `orders` WHERE orderID = ' . $_SESSION['ORDERID'] . ';';
if(!$conn->query($sql)) {
    error('SQL ERROR', 'Error while removing table', true);
} else {
    $_SESSION['ORDERID'] = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Canceled - Wreckless Reykjavík</title>
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
        createTitle('CANCELED');
        info('PAYMENT CANCELED', 'Your payment has been canceled, your cart is still saved if you change your mind');
    ?>
</div>
<?php
include("../includes/html/footer.html");
?>
</body>
</html>