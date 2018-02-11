<?php
	require_once('includes/main.php');

	if(!isset($_SESSION['error-title'], $_SESSION['error-text'])) {
        header('Location: /');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Error - Wreckless Reykjavík</title>
	<?php
		include("includes/html/header.html");
	?>
</head>
<body>
	<?php
		include("includes/html/navigation.php");
	?>
    <div class="full-page">
        <?php
        createTitle('ERROR');
        ?>
        <div class="limit-centered">
            <div class="error-box">
                <h1><?php echo $_SESSION['error-title'] ?></h1>
                <p><?php echo $_SESSION['error-text'] ?></p>
            </div>
        </div>
    </div>
	<?php
		include("includes/html/footer.html");
	?>
</body>
</html>
<?php
$_SESSION['error-title'] = null;
$_SESSION['error-text'] = null;
?>
