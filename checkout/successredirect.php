<?php
require_once('../includes/main.php');
if(!isset($_GET['paymentId'])) {
    error('MISSING VALUES', 'Values are missing that disallows you to view the page');
}
require_once('../includes/checkout/CustomerMananger.php');
use PayPal\Api\Payment;

$paymentId = $_GET['paymentId'];
$payment = Payment::get($paymentId, $paypal);

$transactions = $payment->getTransactions();
$transaction = $transactions[0];

$_SESSION['checkout_paypal'] = $paymentId;
$_SESSION['checkout_id'] = $transaction->invoice_number;
$_SESSION['checkout_payer'] = $_GET['PayerID'];

header("Location: success");