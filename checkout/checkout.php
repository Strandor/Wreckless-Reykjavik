<?php
require_once('../includes/main.php');
require_once('../includes/checkout/CheckoutManagner.php');
require_once('../includes/LoginMananger.php');
require_once('../includes/items/ItemMananger.php');

if (!isset($_SERVER['HTTP_REFERER'])){
    header("Location: ..");
    exit;
}

if(!isLoggedIn() || $_SESSION['item'] == null) {
    header("Location: ../account/register?ref=../checkout/checkout");
}

$itemValues = itemValues($conn);
$promoInfo = null;
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $promoInfo = promoCodeInfo($conn, $_POST["promoBox"]);
    $value = correctValuesCheckout($_POST["name"], $_POST["country"], $_POST["city"], $_POST["address"], $_POST["zip"], $_POST["shipping"]);
    if(!array_filter($value))  {
        require_once('../includes/checkout/GoToCheckout.php');
    }
}
require_once('../includes/commonDesign/CheckoutDesign.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout - Wreckless Reykjavík</title>
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
    <form action="" id="shipping" method="post"">
    <?php
    createTitle('CHECKOUT');
    ?>
    <div class="limit-centered">
        <div class="tableLeft">
                <input type="hidden" name="promoBox" id="promoBox" <?php if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['promoBox'])) {echo 'value="' . $_POST['promoBox'] . '""';}?>>
                <?php
                createCheckoutTable('Your Cart', printOrders($conn));
                createCheckoutTable('Shipping', '<input type="text" name="name" ' . ($_SERVER["REQUEST_METHOD"]=="POST" ? ($value['name'] != null ? 'class="error" placeholder="'. $value['name']. '"' : 'placeholder="Name" value="' . $_POST["name"] .'"') : 'placeholder="Name" value="' . getLoginValues()["name"] . '"') . '/>' .
                    '<select name="country"' . ($_SERVER["REQUEST_METHOD"] == "POST" && isset($value["country"]) ? 'class="error" ><option>' . $value["country"] . '</option>' : '>') . '<option value=IS>Iceland</option></select>' .
                    '<input type="text" name="city" ' . ($_SERVER["REQUEST_METHOD"]=="POST" ? ($value['city'] != null ? 'class="error" placeholder="'. $value['city']. '"' : 'placeholder="City" value="' . $_POST["city"] .'"') : 'placeholder="City"') . '/>' .
                    '<input type="text" name="address" ' . ($_SERVER["REQUEST_METHOD"]=="POST" ? ($value['address'] != null ? 'class="error" placeholder="'. $value['address']. '"' : 'placeholder="Address" value="' . $_POST["address"] .'"') : 'placeholder="Address"') . '/>' .
                    '<input type="text" name="zip" ' . ($_SERVER["REQUEST_METHOD"]=="POST" ? ($value['zip'] != null ? 'class="error" placeholder="'. $value['zip']. '"' : 'placeholder="ZIP" value="' . $_POST["zip"] .'"') : 'placeholder="ZIP"') . '/>');
                createCheckoutTable('Shipping Option', '<div class="radioValue"><div class="right"><div class="price"><p>$35.99</p></div></div><input id="groundShipping" type="radio" name="shipping" value="0"' . (!isset($_POST["shipping"]) || $_POST["shipping"] == 0 ? 'checked' : '') .'><label for="groundShipping"><h4>Ground Shipping</h4> <p>Ships within 5-7 business days</p></label></div>' .
                '<br><div class="radioValue"><div class="right"><div class="price"><p>$75.99</p></div></div><input id="fastShipping" type="radio" name="shipping" value="1"' . (isset($_POST["shipping"]) && $_POST["shipping"] == 1 ? 'checked' : '') .'><label for="fastShipping"><h4>Fast Shipping</h4><p>Ships within 2-3 business days</p></label></div>');
                ?>
        </div>
        <?php
        $promoBox = new extraBoxes();
        $promoBox->title = "PROMO CODE";
        $promoBox->content = '<input type="text" id="promo"><button type=button onclick="addPromo(this)">APPLY</button><br><p style="margin-top: 10px;" id="promoInfo">' . ($_SERVER["REQUEST_METHOD"]=="POST" && isset($promoInfo['price_off']) ? $promoInfo['price_off'] . '% OFF ITEMS' : '') . '</p>';

        $arrayBox = array($promoBox);
        createSideMenu('PAY ORDER', 'Total: $' . ($itemValues['total'] + 35.99), "You'll be redirected to paypal which has it's own privacy policy", '<button form="shipping">Pay Order</button>', $arrayBox);
        ?>
    </div>
    </form>
</div>
<script>
    // TODO FIX LOAD ERROR ON IOS
    document.getElementById('promo').onkeypress = function(e) {
        var event = e || window.event;
        var charCode = event.which || event.keyCode;
        if ( charCode == '13' ) {
            addPromo(e);
            return false;
        }
    }
    document.onsubmit = function() {
        setTimeout(loadImg, 1);
    };
    var quantity = <?php echo $itemValues['quantity'] ?>;
    var total = <?php echo $itemValues['total']; ?>;

    var loadCircle = new Image();
    loadCircle.src = '/assets/vector/load.svg';

    function addPromo(el) {
        if (window.XMLHttpRequest){
            xmlhttp=new XMLHttpRequest();
        } else {
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            document.getElementById('promoInfo').innerHTML = "<img src='/assets/vector/load.svg' style='width: 25px;' '>"
            if (xmlhttp.readyState===4 && xmlhttp.status===200) {
                var json = JSON.parse(xmlhttp.responseText);
                if(json.error != null) {
                    document.getElementById('promoInfo').innerHTML = json.error;
                } else {
                    if(json.min_price > total) {
                        document.getElementById('promoInfo').innerHTML = "Price has to be higher than $" + json.min_price;
                        return;
                    }
                    if(json.max_price < total) {
                        document.getElementById('promoInfo').innerHTML = "Price has to be lower than $" + json.max_price;
                        return;
                    }
                    document.getElementById("promoBox").value = "" + document.getElementById('promo').value;
                    document.getElementById('promoInfo').innerHTML = json.price_off + '% OFF ITEMS';
                }
            }
        };
        xmlhttp.open("GET", "/api/PromoCode.php?promo=" + document.getElementById('promo').value, true);
        xmlhttp.send();
    }
</script>
<?php
include("../includes/html/footer.html");
?>
</body>
</html>