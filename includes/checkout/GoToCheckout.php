<?php
require_once('CustomerMananger.php');

use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\ShippingAddress;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use PayPal\Rest\ApiContext;

$promo = 1;
if(!isset($_POST["promoBox"]) || $_POST["promoBox"] != "") {
    $promo = 1-$promoInfo["price_off"]/100;
    if(isset($promoInfo['min_price']) || isset($promoInfo['max_price'])) {
        if($itemValues['total'] < $promoInfo['min_price'] || $itemValues['total'] > $promoInfo['max_price']) {
            error('Values not approved', 'While we were submitting we noticed that the value were not approved by the js script. If this is an error contact us.');
            exit;
        }
    }
}

$shippingPrice = shippingValueToPrice($_POST['shipping']);

$address = $conn->escape_string($_POST['address']);
$city = $conn->escape_string($_POST['city']);
$country = $conn->escape_string($_POST['country']);
$name = $conn->escape_string($_POST['name']);
$promoName = $conn->escape_string($_POST['promoBox']);
$shipping = $conn->escape_string($_POST['shipping']);
$id = $conn->escape_string(getLoginValues()['id']);
$cart = $conn->escape_string(serialize(getItems()));
$sql = "";

$last_id = null;

$payer = new Payer();
$payer->setPaymentMethod('paypal');

$itemArray = array();
$items = getItems();
foreach($items as $key => $value) {
    $item_info = getItemFromId($value[0], $conn);
    $item = new Item();
    $item->setName($item_info->name)
        ->setPrice($item_info->price*$promo)
        ->setQuantity(1)
        ->setCurrency('USD');
    array_push($itemArray, $item);
}
$total = floor($itemValues['total']*$promo*100) / 100;

$totalAndShipping = $total + $shippingPrice;
if($_POST['promoBox'] == "") {
    $sql = "INSERT INTO orders (payerID, cart, shipping, confirmed, time, name, country, city, address, total) VALUES ($id, '$cart', $shipping, 0, CURRENT_TIMESTAMP, '$name', '$country', '$city', '$address', $totalAndShipping)";
} else {
    $sql = "INSERT INTO orders (payerID, cart, shipping, promo, confirmed, time, name, country, city, address, total) VALUES ($id, '$cart', $shipping, '$promoName', 0, CURRENT_TIMESTAMP, '$name', '$country', '$city', '$address', $totalAndShipping)";
}

if(!$conn->query($sql)) {
    error('SQL ERROR', 'Sorry our backend ran into errors and has been reported. For developers: GoToCheckout: 44;');
} else {
    $last_id = $conn->insert_id;
    $_SESSION['ORDERID'] = $last_id;
}

$itemList = new \PayPal\Api\ItemList();
$itemList->setItems($itemArray);

$shipping_address = new ShippingAddress();
$shipping_address->setCity($_POST['city']);
$shipping_address->setCountryCode($_POST['country']);
$shipping_address->setPostalCode($_POST['zip']);
$shipping_address->setLine1($_POST['address']);
$shipping_address->setRecipientName($_POST['name']);
$itemList->setShippingAddress($shipping_address);

$details = new Details();
$details->setShipping($shippingPrice)
    ->setSubtotal($total);

$amount = new Amount();
$amount->setDetails($details)
    ->setTotal($total + $shippingPrice)
    ->setCurrency('USD');

$transaction = new Transaction();
$transaction->setAmount($amount)
    ->setItemList($itemList)
    ->setDescription('Wreckless Reykjavík')
    ->setInvoiceNumber($last_id);

$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl('http://' . $_SERVER['SERVER_NAME'] . '/checkout/successredirect')
    ->setCancelUrl('http://' . $_SERVER['SERVER_NAME'] . '/checkout/canceled');

$payment = new Payment();
$payment->setIntent('sale')
    ->setPayer($payer)
    ->setRedirectUrls($redirectUrls)
    ->setTransactions([$transaction]);

try {
    $payment->create($paypal);
} catch (Exception $e) {
    var_dump(json_decode($e->getData()));
    exit;
}

header("Location:" . $payment->getApprovalLink());