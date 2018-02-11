<?php
require_once '../includes/main.php';
require_once '../includes/checkout/CheckoutManagner.php';
require_once '../includes/items/ItemMananger.php';
if($_GET['function'] == 'add') {
    if(!isset($_GET['id'], $_GET['size'], $_GET['color'])) {
        die('{ "error" : "Missing values" }');
    }
    $item = getItemFromId($_GET['id'],$conn, 2);
    if($item == null) {
        die('{ "error" : "ID not found }');
    }
    if(!in_array($_GET['size'], $item->sizes)) {
        die('{ "error" : "Size not found" }');
    }
    if(!in_array($_GET['color'], $item->color)) {
        die('{ "error" : "Color not found" }');
    }
    if(count($_SESSION['item']) >= 10) {
        die('{ "error": "You have too many items max 10" }');
    }
    addItem($_GET['id'], $_GET['size'], $_GET['color']);
} else if($_GET['function'] == 'remove') {
    removeItem($_GET['id'], $_GET['size']);
}
echo '{ }';