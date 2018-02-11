<?php
class item {
    public $itemID;
    public $name;
    public $price;
    public $description;
    public $sizes;
    public $color;
    public $images;
}

function shippingIDToInfo($id) {
    if($id == 0) {
        return array(
          "name" => "Ground Shipping",
          "price" => 35.99
        );
    }
    if($id == 1) {
        return array(
            "name" => "Fast Shipping",
            "price" => 75.99
        );
    }
}

function getItemFromId($id, $conn, $values = null) {
    if($values == 1) {
        $sql = "SELECT name, price FROM products WHERE ID='" . $conn->escape_string($id) . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        if (mysqli_num_rows($result) != 0) {
            $item = new item();
            $item->id = $id;
            $item->name = $row['name'];
            $item->price = $row['price'];
            return $item;
        }
    } else if($values == 2) {
        $sql = "SELECT sizes,color FROM products WHERE ID='". $conn->escape_string($id) . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        if(mysqli_num_rows($result) != 0) {
            $item = new item();
            $item->id = $id;
            $item->sizes = json_decode($row['sizes'])->sizes;
            $item->color = json_decode($row['color'])->color;
            return $item;
        }
    } else {
        $sql = "SELECT name, price, description,sizes,color,images FROM products WHERE ID='". $conn->escape_string($id) . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        if(mysqli_num_rows($result) != 0) {
            $item = new item();
            $item->id = $id;
            $item->name = $row['name'];
            $item->price = $row['price'];
            $item->description = $row['description'];
            $item->sizes = json_decode($row['sizes'])->sizes;
            $item->color = json_decode($row['color'])->color;
            $item->images = json_decode($row['images']);
            return $item;
        }
    }
    mysqli_close($conn);
}