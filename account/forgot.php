<?php
  require_once('../includes/main.php');

  if(isLoggedIn() == true) {
    header("Location: profile/orders");
  }

  require_once '../includes/LoginMananger.php';
if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['email'])) {
        $checkValue = checkIfValuesAreValid($_POST['email'])['email'];
        $email = $conn->escape_string($_POST['email']);
        $sql = $conn->query("SELECT id,hash,email FROM accounts WHERE email = '$email'");
        if($sql) {
            if(mysqli_num_rows($sql) != 0) {
                $row = $sql->fetch_assoc();
                require_once('../includes/mail/CommonMail.php');
                sendEmail($row['email'], 'Password Reset Request - Wreckless Reykjavík', 'PASSWORD RESET REQUEST', 'Someone requested an password request. If you did not request this action ignore this email else click this button', $_SERVER['SERVER_NAME'] . '/account/reset_password?id='. $row['id'] . '&hash=' . $row['hash'], 'CLICK TO VERIFY');
            }
        }
    } else {
        error('MISSING VALUES', 'Values are missing in the input box and the script has been exited');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Forgot Password - Wreckless Reykjavík</title>
  <?php
    include("../includes/html/header.html");
  ?>
</head>
<body>
  <?php
    include("../includes/html/navigation.php");
  ?>
  <div class="full-page">
      <div class="limit-centered">
          <?php
          if($_SERVER["REQUEST_METHOD"] == "POST" && $checkValue == null) {
              createTitle('RESET PASSWORD');
              info("We've sent you an email", 'If this email exists in our database we will send an link to reset password.');
          } else {
              ?>
              <h1 class="slim-title">Reset Password</h1>
              <form action="" method="post">
                  <input type="email" name="email" maxlength="50" <?php echo ($_SERVER["REQUEST_METHOD"]=="POST" ? ($checkValue != null ? 'class="error" placeholder="'. $checkValue . '"': 'placeholder="Enter your email"') : 'placeholder="Enter your email"'); ?>>
                  <input type="submit" value="Reset password">
              </form>
              <p>Know your password? <a href="login"><b>Login.</b></a></p>
              <?php
          }
          ?>
      </div>
  </div>
  <?php
    include("../includes/html/footer.html");
  ?>
</body>
</html>
