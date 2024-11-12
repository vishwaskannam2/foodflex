<?php
session_start();
include("includes/db.php");

// Check if the customer is logged in
if (!isset($_SESSION['customer_email'])) {
    header("location: customer_login.php");
    exit();
}

$user = $_SESSION['customer_email'];

// Fetch cart total amount
$sum = isset($_GET['amount']) ? (float)$_GET['amount'] : 0.0;

if (isset($_POST['submit_split_payment'])) {
    $walletAmount = isset($_POST['wallet_amount']) ? (float)$_POST['wallet_amount'] : 0.0;

    // Validate wallet amount
    if ($walletAmount < 0 || $walletAmount > $sum) {
        $error = "Invalid wallet amount.";
    } else {
        $remainingAmount = $sum - $walletAmount;

        // Redirect to the payment gateway for the remaining amount
        header("location: dummy_payment_gateway.php?amount=" . urlencode($remainingAmount) . "&wallet_amount=" . urlencode($walletAmount));
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Split Payment</title>
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
        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="message-box">
        <h2>Split Payment</h2>
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="wallet_amount">Amount to pay from Wallet:</label>
                <input type="number" id="wallet_amount" name="wallet_amount" class="form-control" min="0" max="<?php echo $sum; ?>" step="1" required>
            </div>
            <br>
            <input type="submit" name="submit_split_payment" class="btn btn-success" value="Proceed with Payment">
            <input type="hidden" name="amount" value="<?php echo $sum; ?>">
        </form>
        <br>
        <a href="full_menu.php" class="btn btn-warning">Back to Menu</a>
    </div>
</body>
</html>
