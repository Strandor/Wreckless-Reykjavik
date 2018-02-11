<?php
require_once('../includes/main.php');

if(!isset($_GET['id'], $_GET['hash'])) {
    error('MISSING VALUES', 'Values are missing, contact our support team if you think its an error.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Verify - Wreckless Reykjavík</title>
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
        createTitle('VERIFY');
        $id = $conn->escape_string($_GET['id']);
        $hash = $conn->escape_string($_GET['hash']);
        $sql = $conn->query("SELECT id FROM accounts WHERE id=" . $id . " AND hash='" . $hash . "' AND activated=0;");
        if(mysqli_num_rows($sql) == 0) {
            warning('NO ACCOUNT', 'That hash and email can\'t be found or it has already been verified');
        } else {
            $hash = $conn->escape_string($conn->escape_string(md5(rand(0,1000))));
            $sql = "UPDATE accounts SET activated = 1, hash = '$hash' WHERE id = $id";
            if(!$conn->query($sql)) {
                error('SQL ERROR', 'Error while updating confirmed.');
            } else {
                info('ACCOUNT VERIFIED', 'Thank you so much for verifying your account.');
            }
        }
    ?>
</div>
<?php
include("../includes/html/footer.html");
?>
</body>
</html>