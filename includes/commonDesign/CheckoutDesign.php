<?php
function printOrders($conn, $items = null) {
    if($items == null) {
        $items = getItems();
    }
    $value = "";
    foreach($items as $key => $item_value) {
        $item_info = getItemFromId($item_value[0], $conn, 0);
        $value = $value . '
        <div class="item-shop">
            <img class="item-img" src="/assets/items/' . $item_value[0] . '.jpg">
            <div>
                <a href="/items.php?item_id=' . $item_value[0] . '"><p class="item-shop-title">' . $item_info->name . '</p></a>
                <br>
                <p>Size: ' . $item_value[1] . '</p>
                <br>
                <p>Color: ' . ucfirst($item_value[2]) . '</p>
            </div>
            <div class="right">
                <div class="price">
                    <p>$<span>' . $item_info->price . '</span></p>
                </div>
            </div>
        </div>
        '
        ?>
        <?php
    }
    return $value;
}

function tableItems($conn, $items = null) {
    if($items == null) {
        $items = getItems();
    }
    $value = "";
    foreach($items as $key => $item_value) {
        $item_info = getItemFromId($item_value[0], $conn, 0);
        $value = $value . '
        <tr>
        <td>' . $item_info->name . '</td>
        <td>' . $item_value[1] .'</td>
        <td>' . ucfirst($item_value[2]) . '</td>
        </tr>
        '
        ?>
        <?php
    }
    return $value;
}

function createCheckoutTable($name, $content) {
    echo '
    <div class="infoTable">
        <div class="infoHeader">
            <p>' . $name . '</p>
        </div>
        ' . $content . '
    </div>
    ';
}