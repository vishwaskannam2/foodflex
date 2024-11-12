<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Completed Orders</title>
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
            table-layout: auto; /* Adjust table layout to be responsive */
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
            gap: 5px;
            flex-wrap: wrap; /* Allow actions to wrap on smaller screens */
        }
        .delete-link {
            background-color: #e74c3c;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            text-decoration: none;
            font-weight: bold;
            white-space: nowrap; /* Ensure button text doesn't wrap */
        }
        .table-container {
            max-height: 600px; /* Adjust this height based on your design */
            overflow-y: auto; /* Enable vertical scrolling */
            overflow-x: hidden; /* Hide horizontal scrollbar if not needed */
        }
        @media screen and (max-width: 600px) {
            th, td {
                padding: 8px;
                font-size: 12px;
            }
            .delete-link {
                padding: 2px 5px;
            }
        }
    </style>
</head>
<body>
    <div class="table-container">
        <table>
            <thead>
                <tr align="center">
                    <td colspan="6"><h2>View Completed Orders</h2></td>
                </tr>
                <tr align="center">
                    <th>S.N</th>
                    <th>Food Item</th>
                    <th>Price</th>
                    <th>quantity</th>
                    <th>Roll Number</th>
                    <th>Timestamp (IST)</th>
                    <th>Action</th> <!-- New column for action -->
                </tr>
            </thead>
            <tbody>
                <?php 
                include("includes/db.php");

                // Set the timezone to Indian Standard Time (IST)
                date_default_timezone_set('Asia/Kolkata');

                $get_order = "SELECT * FROM completed_orders ORDER BY order_id DESC"; // Order by order_id or another valid column
                $run_order = mysqli_query($con, $get_order); 
                $i = 0;

                while ($row_order = mysqli_fetch_assoc($run_order)) {
                    $order_id = $row_order['order_id'];
                    $pro_name = $row_order['p_name'];
                    $pro_price = $row_order['p_price'];
                    $c_prn = $row_order['customer_prn'];
                    // Assuming 'order_time' is now a valid column in your completed_orders table
                    $order_time = $row_order['order_time']; 
                    $q = $row_order['quantity'];

                    // Convert server time to IST
                    $ist_timestamp = date('Y-m-d H:i:s', strtotime($order_time));

                    $i++;

                    echo '
                    <tr align="center">
                        <td>'.$i.'</td>
                        <td>'.$pro_name.'</td>
                        <td>'.$pro_price.'</td>
                        <td>'.$q.'</td>
                        <td>'.$c_prn.'</td>
                        <td>'.$ist_timestamp.'</td> <!-- Display timestamp in IST -->
                        <td class="action-links">
                            <a href="completed_orders.php?delete_completed_order='.$order_id.'" class="delete-link">Delete</a>
                        </td> <!-- Delete link -->
                    </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php
    if (isset($_GET['delete_completed_order'])) {
        $delete_id = $_GET['delete_completed_order'];
        $delete_order = "DELETE FROM completed_orders WHERE order_id = '$delete_id'";
        $run_delete = mysqli_query($con, $delete_order);

        if ($run_delete) {
             echo "<script>alert('Order Deleted from Records!'); window.location.href = 'index.php?view_completed_orders';</script>";
        }
    }
    ?>
</body>
</html>
