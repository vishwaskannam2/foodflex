<?php
session_start();
include("includes/db.php");

// Check if the customer is logged in
if (!isset($_SESSION['customer_email'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit("Unauthorized");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];
    $user = $_SESSION['customer_email'];

    // Prepare delete statement
    $deleteQuery = "DELETE FROM orders WHERE p_id = ? AND customer_prn = ?";
    $stmt = $con->prepare($deleteQuery);
    $stmt->bind_param('is', $productId, $user);
    $stmt->execute();

    // Check if delete was successful
    if ($stmt->affected_rows > 0) {
        echo "Item removed successfully";
    } else {
        header("HTTP/1.1 500 Internal Server Error");
        exit("Error removing item from cart");
    }
} else {
    header("HTTP/1.1 400 Bad Request");
    exit("Invalid request");
}
?>
