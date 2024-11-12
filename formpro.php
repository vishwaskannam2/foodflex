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
$sum = isset($_POST['hid']) ? (float)$_POST['hid'] : 0.0;

// Function to format price display
function formatPrice($price) {
    return '₹' . number_format($price, 2);
}

// Function to fetch customer's current balance
function fetchCustomerBalance($con, $user) {
    $query = "SELECT amount FROM customers WHERE customer_prn = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('s', $user);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return isset($row['amount']) ? (float)$row['amount'] : 0.0;
}

$amount = fetchCustomerBalance($con, $user);

if (isset($_POST['sub'])) {
    if (isset($_POST['payment_method'])) {
        $payment_method = $_POST['payment_method'];

        if ($payment_method === 'foodflex_wallet' && $amount >= $sum) {
            // Proceed with order placement using FoodFlex Wallet
            $deductQuery = "UPDATE customers SET amount = amount - ? WHERE customer_prn = ?";
            $stmt = $con->prepare($deductQuery);
            $stmt->bind_param('ds', $sum, $user);
            $stmt->execute();
            header("location: order_confirmation.php");
            exit();
        } elseif ($payment_method === 'online_pay') {
            // Redirect to dummy payment gateway page
            header("location: dummy.php?amount=" . urlencode($sum));
            exit();
        } elseif ($payment_method === 'split_pay') {
            // Redirect to split payment page
            header("location: split_payment.php?amount=" . urlencode($sum));
            exit();
        } else {
            // Invalid payment method selected or insufficient balance
            $paymentError = true;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Select Payment Method</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style type="text/css">
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
        <h2>Select Payment Method</h2>
        <?php if (isset($paymentError) && $paymentError): ?>
            <div class="error-message">Invalid payment method selected or insufficient balance</div>
        <?php elseif ($amount < $sum): ?>
            <div class="error-message">Insufficient Balance in FoodFlex Wallet</div>
        <?php endif; ?>
        <form method="post">
            <label>
                <input type="radio" name="payment_method" value="foodflex_wallet" <?php echo ($amount >= $sum) ? 'checked' : ''; ?>> FoodFlex Wallet (Balance: <?php echo formatPrice($amount); ?>)
            </label>
            <br>
            <label>
                <input type="radio" name="payment_method" value="online_pay"> Online Payment
            </label>
            <br>
            <label>
                <input type="radio" name="payment_method" value="split_pay"> Split Payment
            </label>
            <br><br>
            <input type="submit" class="btn btn-success" name="sub" value="CONFIRM PAYMENT">
            <input type="hidden" name="hid" value="<?php echo $sum; ?>">
        </form>
        <br>
        <a href="full_menu.php" class="btn btn-warning">Back to Menu</a>
    </div>
</body>
</html>