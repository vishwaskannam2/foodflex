<?php
session_start();
include("includes/db.php");

// Check if the customer is logged in
if (!isset($_SESSION['customer_email'])) {
    header("location: customer_login.php");
    exit();
}

$user = $_SESSION['customer_email'];

// Function to fetch ordered products for display and then insert them into orders_admin
function fetchAndInsertOrderedProducts($con, $user) {
    $orderedProducts = [];

    // Fetch orders for the customer
    $fetchOrdersQuery = "SELECT * FROM orders WHERE customer_prn = ?";
    $stmt = $con->prepare($fetchOrdersQuery);
    $stmt->bind_param('s', $user);
    $stmt->execute();
    $result = $stmt->get_result();

    // Insert each order into orders_admin and store ordered products
    $insertOrderQuery = "INSERT INTO orders_admin (p_id, p_name, p_price, customer_prn, order_time, quantity) VALUES (?, ?, ?, ?, ?, ?)";
    $ist_time = date('Y-m-d H:i:s', strtotime('+3 hours 30 minutes')); // Get current time in IST (UTC+5:30)

    while ($row = $result->fetch_assoc()) {
        $id = $row['p_id'];
        $productName = $row['p_name'];
        $quantity = $row['quantity'];
        $pricePerUnit = $row['p_price'];
        $totalPrice = $pricePerUnit * $quantity; // Calculate total price

        $stmt = $con->prepare($insertOrderQuery);
        $stmt->bind_param('isdssi', $id, $productName, $totalPrice, $user, $ist_time, $quantity);
        $stmt->execute();

        // Store ordered products for display
        $orderedProducts[] = [
            'productName' => $productName,
            'price' => $totalPrice,
            'quantity' => $quantity
        ];
    }

    // Clear the cart (orders table) for the customer
    $clearCartQuery = "DELETE FROM orders WHERE customer_prn = ?";
    $stmt = $con->prepare($clearCartQuery);
    $stmt->bind_param('s', $user);
    $stmt->execute();

    return $orderedProducts;
}

// Fetch ordered products and insert them into orders_admin
$orderedProducts = fetchAndInsertOrderedProducts($con, $user);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
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
        .product-list {
            text-align: left;
            margin: 20px auto;
            width: 80%;
        }
        .product-list th, .product-list td {
            padding: 10px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="message-box">
        <h1>Order Confirmation</h1>
        <h3>Ordered Products:</h3>
        <table class="table table-bordered product-list">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderedProducts as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['productName']); ?></td>
                        <td>₹<?php echo htmlspecialchars($product['price']); ?></td>
                        <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-primary">Go to Home</a>
    </div>
</body>
</html>