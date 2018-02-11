<?php
function addItem($id, $size, $color) {
    if(!isset($_SESSION['item'])) {
        $_SESSION['item'] = "";
    }
    $array = $_SESSION['item'];
    if($array != null) {
        array_push($array, array($id, $size, $color));
    } else {
        $array = array(array($id, $size, $color));
    }
    $_SESSION['item'] = $array;
}

function correctValuesCheckout($name, $country, $city, $address, $zip, $shipping) {
    $allowedCountries = array("IS");
    $nameIssue = null;
    $countryIssue = null;
    $zipIssue = null;
    $cityIssues = null;
    $addressIssue = null;
    $shippinsIssue = null;

    if($name == null) {
        $nameIssue = "We require your name";
    } else if(strlen($name) > 50) {
        $nameIssue = "Name is too long";
    }

    if($country == null) {
        $countryIssue = "Please pick at least 1 country";
    } else if(!in_array($country, $allowedCountries)) {
        $countryIssue = "We don't support that country";
    }

    if($city == null) {
        $cityIssues = "We require your city";
    } else if(strlen($city) > 50) {
        $cityIssues = "That looks like an invalid city";
    }

    if($address == null) {
        $addressIssue = "We require your address";
    } else if(strlen($address) > 50) {
        $addressIssue = "That address is too long";
    }

    if($zip == null) {
        $zipIssue = "You have to provide an zip code";
    } else if(strlen($zip) > 16 || !is_numeric($zip)) {
        $zipIssue = "That does not look like an zip code";
    }

    if ($shipping == null || !in_array($shipping, array('0','1'), true )) {
        $shippinsIssue = "Unknown value, please pick a value";
    }

    return array(
        "name" => $nameIssue,
        "country" => $countryIssue,
        "city" => $cityIssues,
        "zip" => $zipIssue,
        "address" => $addressIssue,
        "shipping" => $shippinsIssue
    );
}

function removeItem($key) {
    $array = $_SESSION['item'];
    unset($array[$key]);
    $_SESSION['item'] = $array;
}

function getItems() {
    if(isset($_SESSION['item'])) {
        return $_SESSION['item'];
    } else {
        return null;
    }
}

function itemExists($conn, $id) {
    $sql = "SELECT id FROM products WHERE id=" . $id . ";";
    $result = $conn->query($sql);
    if(mysqli_num_rows($result) == 0) {
        return false;
    } else {
        return true;
    }
}
function promoCodeInfo($conn, $name) {
    $sql = "SELECT price_off,min_price,expires,max_price FROM promocodes WHERE name='" . $conn->escape_string($name) . "'";
    $result = $conn->query($sql);
    if(mysqli_num_rows($result) == 0) {
        return null;
    } else {
        if ($conn->query($sql)) {
            $row = $result->fetch_assoc();
            if($row["expires"] == null || strtotime($row["expires"]) > strtotime("now")) {
                return array(
                    "price_off" => $row["price_off"],
                    "min_price" => $row["min_price"],
                    "max_price" => $row["max_price"]
                );
            }
        }
    }
}

function shippingValueToPrice($value) {
    switch($value) {
        case "0":
            return 35.99;
            break;
        case "1":
            return 75.99;
            break;
        default:
            die('Error: Shipping option unknown');
            break;
    }
}

function itemValues($conn) {
    $items = getItems();
    $quantity = 0;
    $total = 0;
    foreach($items as $key => $item_value) {
        $item_info = getItemFromId($item_value[0], $conn);
        $total += $item_info->price;
        $quantity += 1;
    }

    return array(
        "total" => $total,
        "quantity" => $quantity
    );
}