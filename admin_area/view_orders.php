<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            max-width: 1300px;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: black;
            color: white;
            table-layout: auto; /* Adjust this to ensure table is responsive */
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid white;
            word-wrap: break-word; /* Ensure text wraps within cells */
        }
        th {
            background-color: white;
            color: black;
        }
        tr:nth-child(even) {
            background-color: #333;
        }
        tr:hover {
            background-color: #555;
        }
        .action-links {
            display: flex;
            justify-content: center;
            gap: 5px; /* Add gap between buttons */
        }
        .action-links a {
            display: inline-block;
            margin: 0 5px;
            padding: 3px 8px;
            border-radius: 3px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            white-space: nowrap; /* Ensure buttons don't wrap */
            transition: background-color 0.3s ease; /* Smooth transition for background color */
        }
        .delete-link {
            background-color: #e74c3c;
            color: white;
        }
        .complete-link {
            background-color: #27ae60;
            color: white;
        }
        .delete-link:hover,
        .complete-link:hover {
            background-color: #c0392b; /* Darken delete on hover */
        }
        .complete-link:hover {
            background-color: #219653; /* Darken complete on hover */
        }
        .table-container {
            max-height: 600px; /* Adjust this height based on your design */
            overflow-y: auto; /* Enable vertical scrolling */
            overflow-x: auto; /* Enable horizontal scrolling */
        }
        @media screen and (max-width: 600px) {
            .action-links {
                flex-direction: column; /* Stack buttons vertically on small screens */
            }
            .action-links a {
                margin: 5px 0;
                padding: 10px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="table-container">
        <table>
            <tr align="center">
                <td colspan="7"><h2>View All Orders Here</h2></td>
            </tr>
            <tr align="center">
                <th>S.N</th>
                <th>Food Item</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Roll Number</th>
                <th>Timestamp (IST)</th>
                <th>Action</th>
            </tr>
            <?php 
            // Include database connection
            include("includes/db.php");

            // Set the timezone to Indian Standard Time (IST)
            date_default_timezone_set('Asia/Kolkata');

            // Move orders older than 2 hours to the 'late' table and delete them from 'orders_admin'
            $move_and_delete_query = "
                INSERT INTO late (order_id, p_id, p_name, p_price, customer_prn, order_time, quantity)
                SELECT order_id, p_id, p_name, p_price, customer_prn, order_time, quantity
                FROM orders_admin
                WHERE order_time < NOW() - INTERVAL 2 HOUR;

                DELETE FROM orders_admin
                WHERE order_time < NOW() - INTERVAL 2 HOUR;
            ";

            // Execute the query
            if (mysqli_multi_query($con, $move_and_delete_query)) {
                do {
                    // Use this to skip any results from the query
                    if ($result = mysqli_store_result($con)) {
                        while ($row = mysqli_fetch_row($result)) {
                            // Do nothing, just to clear the result
                        }
                        mysqli_free_result($result);
                    }
                } while (mysqli_next_result($con));
            } else {
                echo "Error: " . mysqli_error($con);
            }

            // Fetch orders from orders_admin table
            $get_order = "SELECT * FROM orders_admin ORDER BY order_id DESC";
            $run_order = mysqli_query($con, $get_order); 
            $i = 0;

            while ($row_order = mysqli_fetch_assoc($run_order)) {
                $order_id = $row_order['order_id'];
                $pro_name = $row_order['p_name'];
                $q = $row_order['quantity'];
                $pro_price = $row_order['p_price'];
                $c_prn = $row_order['customer_prn'];
                $order_time = $row_order['order_time'];

                // Convert server time to IST
                $ist_timestamp = date('Y-m-d H:i:s', strtotime($order_time));

                $i++;

                echo '
                <tr align="center">
                    <td>'.$i.'</td>
                    <td>'.$pro_name.'</td>
                    <td>'.$q.'</td>
                    <td>'.$pro_price.'</td>
                    <td>'.$c_prn.'</td>
                    <td>'.$ist_timestamp.'</td>
                    <td class="action-links">
                        <a href="index.php?view_orders&delete_order='.$order_id.'" class="delete-link">Delete</a>
                        <a href="index.php?view_orders&complete_order='.$order_id.'&customer_prn='.$c_prn.'" class="complete-link">Complete</a>
                    </td>
                </tr>';
            }
            ?>
        </table>
    </div>

    <?php
    // Handle deletion of orders
    if (isset($_GET['delete_order'])) {
        $delete_id = $_GET['delete_order'];
        
        // Fetch the order details
        $get_order_details = "SELECT * FROM orders_admin WHERE order_id = '$delete_id'";
        $run_order_details = mysqli_query($con, $get_order_details);
        $order_details = mysqli_fetch_assoc($run_order_details);

        $customer_prn = $order_details['customer_prn'];
        $order_price = $order_details['p_price'];

        // Ensure the column name matches the actual column name in your `customers` table
        // Replace `customer_prn` with the actual column name if it's different
        $update_customer_amount = "UPDATE customers SET amount = amount + ? WHERE customer_prn = ?";
        $stmt = $con->prepare($update_customer_amount);
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($con->error));
        }

        $bind = $stmt->bind_param('ds', $order_price, $customer_prn);
        if ($bind === false) {
            die('Bind failed: ' . htmlspecialchars($stmt->error));
        }
        
        // Delete the order from orders_admin
        $delete_order = "DELETE FROM orders_admin WHERE order_id = '$delete_id'";
        $run_delete = mysqli_query($con, $delete_order);

        if ($stmt->execute() && $run_delete) {
            echo "<script>alert('Order has been deleted and customer amount updated!'); window.location.href = 'index.php?view_orders';</script>";
        } else {
            echo 'Execute failed: ' . htmlspecialchars($stmt->error);
        }

        $stmt->close();
    }

    // Handle completion of orders
    if (isset($_GET['complete_order']) && isset($_GET['customer_prn'])) {
        $complete_id = $_GET['complete_order'];
        $customer_prn = $_GET['customer_prn'];

        // Fetch the random number associated with the customer_prn
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
        $stmt->bind_result($rand_num);
        $stmt->fetch();
        $stmt->close();

        // Display a form to enter the random number for verification
        echo '<div style="margin-top: 20px; text-align: center;">
                <form method="post" action="verify_order.php">
                    <label for="rand_num">Enter verification number:</label>
                    <input type="hidden" name="complete_id" value="'.$complete_id.'">
                    <input type="hidden" name="customer_prn" value="'.$customer_prn.'">
                    <input type="text" id="rand_num" name="rand_num" required>
                    <button type="submit">Verify and Complete Order</button>
                </form>
              </div>';
    }
    ?>
</body>
</html>
