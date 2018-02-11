<?php
require_once '../includes/main.php';
require_once '../includes/checkout/CheckoutManagner.php';
if(isset($_GET['promo'])) {
    $promo = promoCodeInfo($conn, $_GET['promo']);
    if($promo == null) {
        echo '
        {
        "error": "Found no promo"
        }
        ';
    } else {
        echo '
        {
        "price_off": ' . $promo["price_off"];
        if($promo["min_price"] != null) {
            echo ', "min_price": ' . $promo["min_price"];
        }
        if($promo["max_price"] != null) {
            echo ', "max_price": ' . $promo["max_price"];
        }
        echo '}';
    }
}