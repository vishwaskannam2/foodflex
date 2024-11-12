<?php
// Check if the amount is provided
if (!isset($_GET['amount']) || !is_numeric($_GET['amount'])) {
    header("location: full_menu.php");
    exit();
}

$amount = (float)$_GET['amount'];

// Function to format price display
function formatPrice($price) {
    return '₹' . number_format($price, 2);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dummy Payment Gateway</title>
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
    </style>
</head>
<body>
    <div class="message-box">
        <h2>Dummy Payment Gateway</h2>
        <p>Amount to be paid: <?php echo formatPrice($amount); ?></p>
        <form method="post" action="order_confirmation.php">
            <input type="hidden" name="amount" value="<?php echo $amount; ?>">
            <button type="submit" class="btn btn-success">Confirm Payment</button>
        </form>
        <br>
        <a href="full_menu.php" class="btn btn-warning">Back to Menu</a>
    </div>
</body>
</html>