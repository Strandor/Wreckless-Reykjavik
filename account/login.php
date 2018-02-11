<?php
  require_once('../includes/main.php');

  // Checked if user is already logged in
  if(isLoggedIn() == true) {
    header("Location: profile/orders");
  }

  // Check if requirements are met to login account
  if($_SERVER["REQUEST_METHOD"] == "POST") {
      require_once('../includes/LoginMananger.php');
      if(isset($_GET['ref'])) {
          $host = $_SERVER['HTTP_HOST'];
          $headers = parse_url($_GET['ref'], PHP_URL_HOST);
          if ($headers == null) {
              $server = loginAccount($conn, $_POST['email'], $_POST['password'], $_GET['ref']);
          }
      }
      $server = loginAccount($conn, $_POST['email'], $_POST['password']);
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login - Wreckless Reykjavík</title>
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
          <h1 class="slim-title">Welcome</h1>
          <form action="" method="post">
              <input type="email" name="email" <?php if($_SERVER["REQUEST_METHOD"]=="POST"){if($server['email'] != null){echo 'class="error" placeholder="'.$server['email'].'"';}
              else {echo'placeholder="Enter email" value="' . $_POST["email"] .'"';}} else {echo 'placeholder="Enter email"';} ?> maxlength="50" required>
              <input type="password" name="password" <?php if($_SERVER["REQUEST_METHOD"]=="POST"){if($server['password'] != null){echo 'class="error" placeholder="'.$server['password'].'"';}
              else {echo'placeholder="Enter password" value="' . $_POST["password"] .'"';}} else {echo 'placeholder="Enter password"';} ?> maxlength="50" required>
              <input type="submit" value="Log in">
          </form>
          <p>Don't have an account? <a href="
          <?php
              if(isset($_GET['ref'])) {
                  echo 'register?ref=' . $_GET['ref'];
              } else {
                  echo 'register';
              }
              ?>
        "><b>Register.</b></a></p>
          <a href="forgot">Forgot your password?</a>
      </div>
  </div>
  <?php
    include("../includes/html/footer.html");
  ?>
</body>
</html>
