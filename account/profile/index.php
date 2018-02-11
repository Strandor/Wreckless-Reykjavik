<?php
  require_once('../../includes/main.php');
  if(isLoggedIn()) {
    header( "Location: orders" );
  } else {
    header( "Location: ../login" );
  }
?>
