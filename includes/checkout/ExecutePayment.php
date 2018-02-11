<?php
require_once('CustomerMananger.php');

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

$paymentId = $_SESSION['checkout_paypal'];
$payerID = $_SESSION['checkout_payer'];

$payment = Payment::get($paymentId, $paypal);

$execute = new PaymentExecution();
$execute->setPayerId($payerID);

$transaction = "";
try {
    $payment->execute($execute, $paypal);
    $transactions = $payment->getTransactions();
    $transaction = $transactions[0];

} catch (Exception $e) {
    var_dump(json_decode($e->getData()));
    exit(1);
}
$orderID = $conn->escape_string($transaction->invoice_number);
$sql = $conn->query("UPDATE orders SET confirmed = 1 WHERE orderID = $orderID");

$_SESSION['checkout_paypal'] = null;
$_SESSION['checkout_id'] = null;
$_SESSION['checkout_payer'] = null;
$_SESSION['finish'] = $orderID;
$_SESSION['ORDERID'] = null;
header("Location: finished");