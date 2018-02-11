<?php
require_once('../includes/main.php');

if(!isset($_GET['id'], $_GET['hash'])) {
    error('MISSING VALUES', 'Values are missing, contact our support team if you think its an error.');
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['password'])) {
        require_once('../includes/LoginMananger.php');
        $checkValue = checkIfValuesAreValid('', $_POST['password'])['password'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password - Wreckless Reykjavík</title>
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
        $id = $conn->escape_string($_GET['id']);
        $hash = $conn->escape_string($_GET['hash']);
        $sql = $conn->query("SELECT id FROM accounts WHERE id=" . $id . " AND hash='" . $hash . "';");
        if(mysqli_num_rows($sql) == 0) {
            createTitle('RESET PASSWORD');
            warning('NO ACCOUNT', 'That hash and email can\'t be found or has been changed.');
        } else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['password'])) {
            $hash = $conn->escape_string($conn->escape_string(md5(rand(0,1000))));
            $password = $password = $conn->escape_string(password_hash($_POST['password'], PASSWORD_BCRYPT));;
            $sql = "UPDATE accounts SET password = '$password', activated = 1, hash = '$hash' WHERE id = $id";
            if(!$conn->query($sql)) {
                error('SQL ERROR', 'Error while updating confirmed.');
            } else {
                createTitle('- RESET PASSWORD');
                info('PASSWORD CHANGED', 'Your password has been changed.');
            }
        } else {
            ?>
            <h1 class="slim-title">Reset Password</h1>
            <form action="" method="post">
                <input type="password" name="password" maxlength="50" <?php echo ($_SERVER["REQUEST_METHOD"]=="POST" ? ($checkValue != null ? 'class="error" placeholder="'. $checkValue . '"': 'placeholder="Enter your new password"') : 'placeholder="Enter your new password"'); ?>>
                <input type="submit" value="Reset password">
            </form>
            <?php
        }
    ?>
</div>
<?php
include("../includes/html/footer.html");
?>
</body>
</html>