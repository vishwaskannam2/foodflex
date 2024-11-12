<?php 
if (!isset($_SESSION['user_email'])) {
    echo "<script>window.open('login.php?not_admin=You are not an Admin!','_self')</script>";
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .table-container {
            max-height: 600px; /* Adjust this height based on your design */
            overflow-y: auto; /* Enable vertical scrolling */
            overflow-x: hidden; /* Hide horizontal scrollbar if not needed */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
        }

        table th, table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ccc;
        }

        table th {
            background-color: #333;
            color: #fff;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #ddd;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="table-container">
    <table>
        <tr>
            <th colspan="7"><h2>View All Products Here</h2></th>
        </tr>
        <tr>
            <th>S.N</th>
            <th>Title</th>
            <th>Price</th>
            <th>Quantity</th> <!-- Added column for Quantity -->
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        <?php 
        include("includes/db.php");
        $get_pro = "SELECT * FROM products";
        $run_pro = mysqli_query($con, $get_pro); 
        $i = 0;
        while ($row_pro = mysqli_fetch_array($run_pro)) {
            $pro_id = $row_pro['product_id'];
            $pro_title = $row_pro['product_title'];
            $pro_price = $row_pro['product_price'];
            $pro_quantity = $row_pro['quantity']; // Fetch quantity from database
            $i++;
        ?>
        <tr>
            <td><?php echo $i;?></td>
            <td><?php echo $pro_title;?></td>
            <td><?php echo $pro_price;?></td>
            <td><?php echo $pro_quantity;?></td> <!-- Display the quantity -->
            <td><a href="index.php?edit_pro=<?php echo $pro_id; ?>">Edit</a></td>
            <td><a href="delete_pro.php?delete_pro=<?php echo $pro_id;?>">Delete</a></td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>

<?php } ?>
