<?php
	require_once('includes/main.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Home - Wreckless Reykjavík</title>
	<link href="stylesheet/home.css" rel="stylesheet" type="text/css">
	<?php
		include("includes/html/header.html");
	?>
</head>
<body>
	<?php
		include("includes/html/navigation.php");
	?>
	<div class="full-page">
	    <div class="welcome-sign">
		    <h3><span class="we-are">We are</span> <span class="wreckless">WRECKLESS</span></h3>
		    <h3 class="reykjavik">REYKJAVÍK</h3>
	    </div>
        <div class="centered">
            <img alt="more click" class="bottom" src="assets/vector/more.svg">
        </div>
    </div>
	<div class="bottom-padding">
		<div class="centered">
			<h3 class="title">FEATURED ITEMS</h3>

			<?php
				$sql = "SELECT name, price, ID FROM products LIMIT 3";
				$result = $conn->query($sql);

				if (mysqli_num_rows($result) > 0) {
					while($row = $result->fetch_assoc()) { ?>
						<div class="item-box">
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
		include("includes/html/footer.html");
	?>
</body>
</html>
