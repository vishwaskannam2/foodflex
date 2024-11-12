<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_email'])) {
    echo "<script>window.open('login.php?not_admin=You are not an Admin!','_self')</script>";
    exit();
}

include("includes/db.php"); // Include database connection

$customer_info = "";
$error_message = "";

// Handle form submission for searching customer
if(isset($_POST['search_customer'])){
    $customer_prn = mysqli_real_escape_string($con, $_POST['customer_prn']);
    $get_customer = "SELECT * FROM customers WHERE customer_prn='$customer_prn'";
    $run_customer = mysqli_query($con, $get_customer);

    if($run_customer && mysqli_num_rows($run_customer) > 0){
        $row_customer = mysqli_fetch_assoc($run_customer);
        $c_name = $row_customer['customer_name'];
        $c_email = $row_customer['customer_email'];
        $c_contact = $row_customer['customer_contact'];
        $c_amount = $row_customer['amount'];
        $customer_info = "
            <div class='result'>
                <p><strong>Name:</strong> $c_name</p>
                <p><strong>Email:</strong> $c_email</p>
                <p><strong>Phone Number:</strong> $c_contact</p>
                <p><strong>Amount:</strong> $c_amount</p>
            </div>
        ";
    } else {
        $error_message = "<div class='error'>Customer not found.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Customer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            margin: 20px 0;
        }
        input[type="text"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        input[type="submit"], .home-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-align: center;
            display: inline-block;
        }
        .home-button {
            background-color: #007BFF;
            margin-top: 10px;
            text-decoration: none;
        }
        .result {
            margin-top: 20px;
            padding: 10px;
            background-color: #e7f3e7;
            border-left: 5px solid #4CAF50;
        }
        .error {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8d7da;
            border-left: 5px solid #f5c2c7;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Search Customer</h2>
    <form method="post" action="search_customer.php">
        <input type="text" name="customer_prn" placeholder="Enter Roll Number" required>
        <input type="submit" name="search_customer" value="Search">
    </form>
    <?php 
    if (!empty($customer_info)) {
        echo $customer_info;
    }
    if (!empty($error_message)) {
        echo $error_message;
    }
    ?>
    <a href="index.php" class="home-button">Return to Home</a>
</div>
</body>
</html>
