<?php
require_once('../includes/main.php');
require_once('../includes/checkout/CheckoutManagner.php');
require_once('../includes/items/ItemMananger.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cart - Wreckless Reykjavík</title>
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
    createTitle('CART');
    ?>
    <div class="limit-centered">
        <div class="tableLeft">
            <?php
            $items = getItems();
            $total = 0;
            if($items != null) {
            foreach($items as $key => $value){
                $item_info = getItemFromId($value[0], $conn);
                $total += $item_info->price;
                ?>
                <div class="item-shop">
                    <img class="item-img" <?php echo 'src="/assets/items/' . $value[0] . '.jpg"'?>>
                    <div>
                        <a href="/items.php?item_id=<?php echo $value[0]?>"><p class="item-shop-title"><?php echo $item_info->name?></p></a>
                        <br>
                        <p>Size: <?php echo $value[1]?></p>
                        <br>
                        <p>Color: <?php echo ucfirst($value[2])?></p>
                    </div>
                    <div class="right">
                        <div class="price">
                            <p>$<span><?php echo $item_info->price?></span></p>
                            <img src="/assets/vector/closeFill.svg" onclick="removeItem(this, <?php echo $key?>)"/>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
        $paymentBox = new extraBoxes();
        $paymentBox->title = "PAYMENT METHODS";
        $paymentBox->content = 'Visa';

        $shippingBox = new extraBoxes();
        $shippingBox->title = "SHIPPING PRICES";
        $shippingBox->content = '<b>Ground Shipping:</b> $35.99 <br> <b>Fast Shipping:</b> $75.99';

        $arrayBox = array($paymentBox, $shippingBox);
        createSideMenu('CHECKOUT', 'Total: $<span id="total">' . $total . '</span>', 'Tax and shipping are calculated at the next page', '<a href="checkout"><button>Checkout</button></a>', $arrayBox);
        ?>
        </div>
<?php
} else {
    warning('NO ITEMS', 'You have not items, you should explore the store');
}
?>
</div>
</div>
</div>
<script>
    var total = <?php echo $total ?>

        function updateTotal(prize) {
            var bag = document.getElementById("total");
            var bagValue = (total - prize).toFixed(2);
            bag.innerHTML = 'Total: $' + bagValue;
            if(bagValue == 0) {
                document.getElementById("total").parentElement.parentElement.parentElement.outerHTML = "<div class=\"limit-centered\"> <div class=\"tableLeft\"> <div class=\"limit-centered\"> <div class=\"error-box\"> <h1>NO ITEMS</h1> <p>You have not items, you should explore the store</p> </div> </div> </div> </div>";
            }
            total = bagValue;
        }

    function removeItem(el, id) {
        el.parentElement.parentElement.parentElement.outerHTML = "";
        if (window.XMLHttpRequest){
            xmlhttp=new XMLHttpRequest();
        } else {
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState===4 && xmlhttp.status===200) {
                updateTotal(el.parentElement.children[0].children[0].innerHTML);
            }
        };
        xmlhttp.open("GET", "/api/Cart.php?id=" + id + '&function=remove', true);
        xmlhttp.send();
    }
</script>
<?php
include("../includes/html/footer.html");
?>
</body>
</html>
