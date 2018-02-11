<?php
  require_once('../includes/main.php');

  // Checked if user is already logged in
  if(isLoggedIn() == true) {
    header("Location: profile/orders");
  }

  // Check if requirements are met to create account
  if($_SERVER["REQUEST_METHOD"] == "POST") {
      require_once('../includes/LoginMananger.php');
      if(isset($_GET['ref'])) {
          $host = $_SERVER['HTTP_HOST'];
          $headers = parse_url($_GET['ref'], PHP_URL_HOST);
          if ($headers == null) {;
              $server = createAccount($conn, $_POST['text'], $_POST['email'], $_POST['password'], $_GET['ref']);
              exit;
          }
      }
      $server = createAccount($conn, $_POST['text'], $_POST['email'], $_POST['password']);
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Register - Wreckless Reykjavík</title>
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
          <h1 class="slim-title">Register</h1>
          <form action="" method="post" autocomplete="off">
              <input type="text" name="text" <?php if($_SERVER["REQUEST_METHOD"]=="POST"){if($server['name']!=null){echo 'class="error" placeholder="'.$server['name'].'"';}
              else {echo'placeholder="Enter full name" value="' . $_POST["text"] .'"';}} else {echo 'placeholder="Enter full name"';} ?> maxlength="50" required>
              <input type="email" name="email" <?php if($_SERVER["REQUEST_METHOD"]=="POST"){if($server['email'] != null){echo 'class="error" placeholder="'.$server['email'].'"';}
              else {echo'placeholder="Enter email" value="' . $_POST["email"] .'"';}} else {echo 'placeholder="Enter email"';} ?> maxlength="50" required>
              <input type="password" name="password" <?php if($_SERVER["REQUEST_METHOD"]=="POST"){if($server['password'] != null){echo 'class="error" placeholder="'.$server['password'].'"';}
              else {echo'placeholder="Enter password" value="' . $_POST["password"] .'"';}} else {echo 'placeholder="Enter password"';} ?> maxlength="50" required>
              <div class="checkboxDiv">
                  <input type="checkbox" id="c1" name="checkbox" <?php if($_SERVER["REQUEST_METHOD"]=="POST"){if(isset($_POST['checkbox']) && $_POST["checkbox"] == "on") {echo "checked";}
                  else {echo 'class="error-check"';}}
                  ?>/>
                  <label for="c1"><span></span>I've read and agree to the <a href="/legal/tos"><b>Terms of Service</b></a> and <a href="/legal/privacy"><b>Privacy Policy</b></a>.</label>
              </div>
              <input type="submit" value="Register">
          </form>
          <p>Already have an account? <a href="
          <?php
              if(isset($_GET['ref'])) {
                  echo 'login?ref=' . $_GET['ref'];
              } else {
                  echo 'login';
              }
              ?>
        "><b>Log in.</b></a></p>
      </div>
  </div>
  <?php
    include("../includes/html/footer.html");
  ?>
</body>
</html>
