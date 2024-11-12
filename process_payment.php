<?php
session_start();
include("includes/db.php");

// Check if the customer is logged in
if (!isset($_SESSION['customer_email'])) {
    header("location: customer_login.php");
    exit();
}

$user = $_SESSION['customer_email'];

// Fetch the amounts from the form
$remainingAmount = isset($_POST['amount']) ? (float)$_POST['amount'] : 0.0;
$walletAmount = isset($_POST['wallet_amount']) ? (float)$_POST['wallet_amount'] : 0.0;

// Validate the amounts
if ($walletAmount < 0 || $remainingAmount < 0) {
    die("Invalid amounts.");
}

// Deduct the wallet amount from the customer's balance
$deductQuery = "UPDATE customers SET amount = amount - ? WHERE customer_prn = ?";
$stmt = $con->prepare($deductQuery);
$stmt->bind_param('ds', $walletAmount, $user);
$stmt->execute();

// Here you would handle the remaining amount with the payment gateway

// After payment is successful, redirect to confirmation or orders page
header("location: order_confirmation.php");
exit();
?>
