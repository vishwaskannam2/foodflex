<?php
include("includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complete_id = $_POST['complete_id'];
    $customer_prn = $_POST['customer_prn'];
    $rand_num_input = $_POST['rand_num'];

    // Fetch the actual random number associated with the customer_prn
    $get_random_number = "SELECT random_number FROM rand WHERE customer_prn = ?";
    $stmt = $con->prepare($get_random_number);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($con->error));
    }

    $bind = $stmt->bind_param('s', $customer_prn);
    if ($bind === false) {
        die('Bind failed: ' . htmlspecialchars($stmt->error));
    }

    $stmt->execute();
    $stmt->bind_result($rand_num_db);
    $stmt->fetch();
    $stmt->close();

    // Compare input random number with the database value
    if ($rand_num_input == $rand_num_db) {
        // Fetch order details including quantity
        $get_order_details = "SELECT p_id, p_name, p_price, quantity, order_time FROM orders_admin WHERE order_id = ?";
        $stmt = $con->prepare($get_order_details);
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($con->error));
        }

        $stmt->bind_param('i', $complete_id);
        $stmt->execute();
        $stmt->bind_result($p_id, $p_name, $p_price, $quantity, $order_time);
        $stmt->fetch();
        $stmt->close();

        // Insert into completed_orders
        $insert_completed_order = "INSERT INTO completed_orders (p_id, p_name, p_price, quantity, customer_prn, order_time) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($insert_completed_order);
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($con->error));
        }

        $stmt->bind_param('isisss', $p_id, $p_name, $p_price, $quantity, $customer_prn, $order_time);

        // Delete from orders_admin
        $delete_order = "DELETE FROM orders_admin WHERE order_id = ?";
        $stmt_delete = $con->prepare($delete_order);
        if ($stmt_delete === false) {
            die('Prepare failed: ' . htmlspecialchars($con->error));
        }

        $stmt_delete->bind_param('i', $complete_id);
        $run_delete = $stmt_delete->execute();

        if ($stmt->execute() && $run_delete) {
            echo "<script>alert('Order has been marked as completed!'); window.location.href = 'index.php?view_orders';</script>";
        } else {
            echo 'Execute failed: ' . htmlspecialchars($stmt->error);
        }

        $stmt->close();
        $stmt_delete->close();
    } else {
        echo "<script>alert('Invalid random number! Please try again.'); window.location.href = 'index.php?view_orders';</script>";
    }
}
?>
