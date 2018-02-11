<?php
	require_once('includes/main.php');
	require_once('includes/items/ItemMananger.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Items - Wreckless Reykjavík</title>
	<?php
		include("includes/html/header.html");
	?>
    <script src="/scripts/items.js"></script>
</head>
<body>
<?php
	include("includes/html/navigation.php");
	if(isset($_GET['item_id']) && is_numeric($_GET['item_id'])) {
		$item = getItemFromId($_GET['item_id'], $conn);
		if($item == null) {
		    error('ITEM NOT FOUND', 'Sorry we could not find that item');
        }
	?>
		<div class="full-page" style="overflow: hidden;">
            <div class="centered">
                <div class="item-info">
                    <div class="inline-div">
                        <img src="assets/items/<?php echo $item->images->main;?>" id="mainImage"/>
                    </div>
                    <div class="images">
                        <?php
                        $first = true;
                        foreach($item->images->images as $key => $value) {
                            if($first) {
                                echo '<img src="/assets/items/' . $item->images->main . '" onclick="changeImage(this)" style="border-color: #3F5765"; id="imageChecked">';
                                $first = false;
                            }
                            echo '<img src="/assets/items/' . $value . '" onclick="changeImage(this)">';
                        }
                        $first = true;
                        ?>
                    </div>
                    <div class="inline-div">
                        <h1 id="title_item"><?php echo $item->name;?></h1>
                        <p>$<?php echo $item->price;?></p>
                        <p class="description"><?php echo $item->description;?></p>
                        <div class="color" style="display: table">
                        <?php
                        function valueToColor($value) {
                            if($value == "black") {
                                return '#333';
                            }
                            if($value == "gray") {
                                return '#D3D3D3';
                            }
                            if($value == "dark gray") {
                                return '#696969';
                            }
                            if($value == "white") {
                                return '#fff';
                            }
                        }

                        $first = true;
                        foreach($item->color as $key => $value) {
                            if($first == true) {
                                echo '<input type="radio" value="' . $value . '" name="color" style="background: ' . valueToColor($value)  . '" checked>';
                                $first = false;
                            } else {
                                echo '<input type="radio" value="' . $value . '" name="color" style="background: ' . valueToColor($value). '">';
                            }
                        }
                        ?>
                        </div>
                        <div class="valuesItems">
                            <select id="sizeOptions">
                                <?php
                                foreach($item->sizes as $key => $value) {
                                    echo '<option>' . $value . '</option>';
                                }
                                ?>
                            </select>
                            <button onclick="addToCart(<?php echo $_GET['item_id']?>)" ">ADD TO CART</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	<?php
	} else {
	?>
		<div class="full-page">
            <div class="limit-centered">
            <?php
            createTitle("ITEMS");
            ?>
            <div class="options">
                <select onchange="updateSelect('size', this)">
                    <option>Size</option>
                    <option>S</option>
                    <option>M</option>
                    <option>L</option>
                    <option>XL</option>
                </select>
            </div>
            </div>
            <div class="centered">
                <?php
                $sql = "SELECT name, price, ID, sizes FROM products";
                $result = $conn->query($sql);

                if (mysqli_num_rows($result) > 0) {
                    while($row = $result->fetch_assoc()) { ?>
                        <div class="item-box" data-size='<?php echo $row['sizes'] ?>'>
                            <div class="max-img">
                                <img class="item-img" alt="item shop" src="/assets/items/<?php echo $row["ID"]?>.jpg">
                            </div>
                            <h3><?php echo $row["name"]?></h3>
                            <p>$<?php echo $row["price"]?></p>
                            <a href="items?item_id=<?php echo $row["ID"] ?>"><button>LEARN MORE</button></a>
                        </div>
                        <?php
                    }
                } else {?>
                    <div class="error-box">
                        <p>Looks like there are no items right now</p>
                    </div>
                    <?php
                }
                $conn->close();
                ?>
            </div>
		</div>
	<?php
	}
	include("includes/html/footer.html");
?>
<div class="message" id="message">
    <img src='/assets/items/<?php echo $_GET['item_id'];?>.jpg'>
    <div><p><?php echo $item->name;?></p><p>Added to cart</p></div>
</div>
<script>s
    function itemMessage() {
        var message = document.getElementById('message');
        message.innerHTML = "<img src='/assets/items/<?php echo $_GET['item_id'];?>.jpg'> <div><p><?php echo $item->name;?></p><p>Added to cart</p></div>";
        message.style.animation = "";
        message.style.webkitAnimation = "";
        setTimeout(function() {
            message.style.animation = "message 5s linear 0s";
            message.style.webkitAnimation = "message 5s linear 0s";
        }, 20);
    }
</script>
</body>
</html>
