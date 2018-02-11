<?php
  session_start();
  require_once('../includes/LoginMananger.php');
  clearSession();
  header("Location: login");
?>
