<?php
require_once('../includes/main.php');
if(!isset($_SESSION['checkout_payer'] , $_SESSION['checkout_id'], $_SESSION['checkout_paypal'])) {
    header("Location: ..");
    die('Redirecting to home page...');
}

require_once('../includes/items/ItemMananger.php');

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['checkbox']) && $_POST['checkbox'] == 'on') {
        require_once('../includes/checkout/ExecutePayment.php');
        exit;
    }
}
$paymentId = $_SESSION['checkout_paypal'];
require_once('../includes/commonDesign/CheckoutDesign.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Success - Wreckless Reykjavík</title>
    <?php
    include("../includes/html/header.html");
    ?>
</head>
<body>
<?php
include("../includes/html/navigation.php");
?>
<div class="centered" id="loadCircleDiv" style="display: none; position: fixed; z-index: 100; background-color: #EFEFEF; width: 100%; height: 100%;">
    <img src="/assets/vector/load.svg" style="position: absolute; top: 50%; margin-top: -125px; margin-left: -75px;"/>
</div>
<div class="full-page">
    <?php
    createTitle('PLACE ORDER');
    ?>
    <div class="limit-centered">
        <div class="tableLeft">
            <form action="" method="post" name="review" id="review">
                <?php
                $orderID = $conn->escape_string($_SESSION['checkout_id']);
                $sql = $conn->query("SELECT payerID,cart,name,city,address,shipping,total FROM orders WHERE orderID='" . $orderID . "'");
                if(mysqli_num_rows($sql) == 0) {
                    error('SQL ERROR', 'Error while finding orderID. For Developer:(Success.php:54 Order ID:' . $orderID . ')', true, $conn);
                }
                $row = $sql->fetch_assoc();

                $accountID = $row['payerID'];
                $accountSQL = $conn->query("SELECT email FROM accounts WHERE id='$accountID'");
                if(mysqli_num_rows($accountSQL) == 0) {
                    ERROR('SQL ERROR', 'Error while finding email: For Developer:(Success.php:62 Order ID:' . $accountID . ')', true, $conn);
                }
                $accountRow = $accountSQL->fetch_assoc();
                createCheckoutTable('Your Items', printOrders($conn, unserialize($row['cart'])));
                $info = shippingIDToInfo($row['shipping']);
                createCheckoutTable('Your Info', '<div><p class="orderTitle">Receiver Information:</p><p class="orderSub"> ' . htmlspecialchars($row['name']) . ', ' . htmlspecialchars($accountRow['email']) . '</p></div><div><p class="orderTitle">Shipping to:</p><p class="orderSub">' . htmlspecialchars($row['address']) . ', ' . htmlspecialchars($row['city']) . '</p></div><div><p class="orderTitle">Shipping Method:</p><p class="orderSub">' . $info['name'] . ', $' . $info['price'] . '</p></div>');
                createCheckoutTable('Legal', '<div class="checkboxDiv" style="margin-left: 15px; margin-top: 20px;"><input type="checkbox" id="c1" name="checkbox" ' . ($_SERVER["REQUEST_METHOD"]=="POST" ? ((isset($_POST['checkbox']) && $_POST["checkbox"] == "on") ? "checked" : 'class="error-check"') : '') .'/><label for="c1"><span></span>I\'ve read and agree to the <a href="/legal/tos"><b>Terms of Service</b></a> and <a href="/legal/privacy"><b>Privacy Policy</b></a>.</label></div>');
                ?>
            </form>
        </div>
        <?php
        $promoBox = new extraBoxes();
        $promoBox->title = "NEED HELP";
        $promoBox->content = 'You were redirected from paypal. Now we want to give you a final chance to review your order before we charge your card.<br><br><b>Need further help?</b><br>Contact: support@domain-example.com';

        $arrayBox = array($promoBox);
        createSideMenu('PLACE ORDER', 'Total: $' . $row['total'], "This action will charge your card", '<button form="review">Place Order</button>', $arrayBox);
        ?>
        </div>
    </div>
</div>
<script>
    document.onsubmit = function() {
        setTimeout(loadImg, 500);
    };
</script>
<?php
include("../includes/html/footer.html");
?>
</body>
</html>