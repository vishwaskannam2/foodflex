<?php
include("includes/db.php");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to delete orders older than 2 hours based on IST
$sql = "DELETE FROM orders_admin 
        WHERE TIMESTAMPDIFF(HOUR, order_time, CONVERT_TZ(NOW(), '+00:00', '+05:30')) > 2";

if ($conn->query($sql) === FALSE) {
    echo "Error deleting old orders: " . $conn->error;
}

// Close connection
$conn->close();
?>
