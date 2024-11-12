<?php 
session_start(); 

if(!isset($_SESSION['user_email'])){
    echo "<script>window.open('login.php?not_admin=You are not an Admin!','_self')</script>";
    exit();
}

include("includes/db.php");

// Set the timezone to Indian Standard Time (IST)
date_default_timezone_set('Asia/Kolkata');
// Calculate this month's income
$month_date = date('Y-m');
$month_income_query = "SELECT SUM(p_price) AS month_income FROM completed_orders WHERE DATE_FORMAT(order_time, '%Y-%m') = '$month_date'";
$month_income_result = mysqli_query($con, $month_income_query);

if (!$month_income_result) {
    die('Error executing month income query: ' . mysqli_error($con));
}

$month_income_row = mysqli_fetch_assoc($month_income_result);
$month_income = $month_income_row['month_income'] ? $month_income_row['month_income'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="styles/style.css">
    <style>
        /* General styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Header styles */
        #header {
            background-color: #333;
            text-align: center;
            padding: 9px 0;
            width: 80%;
            border-radius: 8px;
        }

        #header p {
            font-size: 36px;
            color: #fff;
            margin: 0;
        }

        /* Main container styles */
        .container {
            display: flex;
            justify-content: space-around;
            width: 100%;
            max-width: 1200px;
            margin-top: 20px;
        }

        /* Right panel styles */
        #right {
            flex: 1;
            background-color: #444;
            padding: 20px;
            border-radius: 8px;
            color: #fff;
            margin-right: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        #right h2 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
        }

        #right a {
            display: block;
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            margin-bottom: 10px;
            transition: color 0.3s ease;
        }

        #right a:hover {
            color: #4CAF50;
        }

        .income-details {
            font-size: 16px; /* Make the text smaller */
            text-align: center;
            margin-top: 20px;
        }

        /* Left panel styles */
        #left {
            flex: 3;
            background-color: #eee;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* Responsive adjustments */
        @media screen and (max-width: 960px) {
            .container {
                flex-direction: column;
                align-items: center;
            }

            #right, #left {
                width: 100%;
                margin-right: 0;
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <div id="header">
        <p>FOOD FLEX ADMIN</p>
    </div>

    <div class="container">
        <div id="right">
            <h2><strong>Manage Content</strong></h2>
            <a href="index.php?insert_product">Insert New Product</a>
            <a href="index.php?view_products">View All Products</a>
            <a href="index.php?view_orders">View Orders</a>
            <a href="index.php?view_completed_orders">View Completed Orders</a>
            <a href="index.php?late">View Late Orders</a>
            <a href="search_customer.php">Search Customer</a>
            <strong><a style="color: red;" href="index.php?add_amount">Add Amount</a></strong>
            <a style="color: grey" href="logout.php">Admin Logout</a>
            <div class="income-details">
                <p>This Month's counter Amount: ₹<?php echo $month_income; ?></p>
            </div>
        </div>
        
        <div id="left">
            <?php 
            if(isset($_GET['insert_product'])){
                include("insert_product.php"); 
            }
            if(isset($_GET['view_products'])){
                include("view_products.php"); 
            }
            if(isset($_GET['edit_pro'])){
                include("edit_pro.php"); 
            }
            if(isset($_GET['view_orders'])){
                include("view_orders.php"); 
            }
            if(isset($_GET['view_completed_orders'])){
                include("completed_orders.php"); 
            }
            if(isset($_GET['late'])){
                include("late.php"); 
            }
            if(isset($_GET['add_amount'])){
                include("add_amount.php"); 
            }
            if(isset($_GET['search_customer'])){ 
                include("search_customer.php"); 
            }
            ?>
        </div>
    </div>
</body>
</html>
