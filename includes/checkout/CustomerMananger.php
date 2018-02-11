<?php
require_once('../includes/paypal/vendor/autoload.php');

$paypal = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential('** cID **', '** cSecret **')
);
