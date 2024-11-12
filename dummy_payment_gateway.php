<?php
session_start();
include("includes/db.php");

// Check if the customer is logged in
if (!isset($_SESSION['customer_email'])) {
    header("location: customer_login.php");
    exit();
}

$user = $_SESSION['customer_email'];

// Fetch the amount to be paid online and the wallet amount from query parameters
$remainingAmount = isset($_GET['amount']) ? (float)$_GET['amount'] : 0.0;
$walletAmount = isset($_GET['wallet_amount']) ? (float)$_GET['wallet_amount'] : 0.0;

// Function to format price display
function formatPrice($price) {
    return '₹' . number_format($price, 2);
}

// Simulate processing payment
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dummy Payment Gateway</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f5f5f5;
            text-align: center;
            padding-top: 50px;
        }
        .message-box {
            display: inline-block;
            text-align: center;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="message-box">
        <h2>Payment Gateway</h2>
        <p>You are about to pay: <?php echo formatPrice($remainingAmount); ?></p>
        <form method="post" action="process_payment.php">
            <!-- Simulate payment processing -->
            <input type="submit" class="btn btn-success" value="Pay Now">
            <input type="hidden" name="amount" value="<?php echo $remainingAmount; ?>">
            <input type="hidden" name="wallet_amount" value="<?php echo $walletAmount; ?>">
        </form>
        <br>
        <a href="full_menu.php" class="btn btn-warning">Back to Menu</a>
    </div>
</body>
</html>
