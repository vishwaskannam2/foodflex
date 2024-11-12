<?php
session_start();
include("functions/functions.php");
include("includes/db.php");

$message = ""; // Variable to store error message

if(isset($_POST['register'])){
    $c_name = $_POST['c_name'];
    $c_email = $_POST['c_email'];
    $c_pass = $_POST['c_pass'];
    $c_contact = $_POST['c_contact'];
    $c_prn = $_POST['c_prn'];
    
    // Check if email, roll number, or contact number already exists
    $check_customer = "SELECT * FROM customers WHERE customer_email=? OR customer_prn=? OR customer_contact=?";
    $stmt = mysqli_prepare($con, $check_customer);
    mysqli_stmt_bind_param($stmt, "sss", $c_email, $c_prn, $c_contact);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $check_rows = mysqli_stmt_num_rows($stmt);
    
    if($check_rows > 0) {
        $message = "Email, roll number, or contact number already exists. Please try with different details.";
    } else {
        // Hash the password
        $hashed_pass = password_hash($c_pass, PASSWORD_DEFAULT);
        
        // Insert new customer if no duplicate found
        $insert_c = "INSERT INTO customers (customer_name, customer_email, customer_pass, customer_contact, customer_prn) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $insert_c);
        mysqli_stmt_bind_param($stmt, "sssss", $c_name, $c_email, $hashed_pass, $c_contact, $c_prn);
        
        if(mysqli_stmt_execute($stmt)){
            $_SESSION['customer_email'] = $c_email; 
            $_SESSION['registration_success'] = true; // Set session variable for success
            echo "<script>alert('Account has been created successfully')</script>";
            echo "<script>window.open('customer_login.php','_self')</script>";
            exit(); // Ensure script execution stops after redirection
        } else {
            $message = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Register</title>
    <link rel="stylesheet" href="styles/style.css" media="all">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        body, html {
            height: 100%;
            background: linear-gradient(to right, #ffefba, #ffffff);
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
        }
        .navbar-wrapper {
            position: absolute;
            top: 0;
            width: 100%;
        }
        .navbar {
            border-radius: 0;
        }
        .container {
            text-align: center;
            margin-top: 50px;
        }
        .welcome {
            font-family: 'Raleway', sans-serif;
            font-size: 24px;
            color: #333;
            margin-bottom: 25px;
        }
        .card-container {
            background: white;
            padding: 40px 40px;
            margin: 0 auto 25px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2), 0 6px 6px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-in-out;
        }
        .login_title {
            font-family: 'Raleway', sans-serif;
            font-size: 20px;
            color: #777;
            margin-bottom: 20px;
        }
        .form-signin {
            max-width: 300px;
            margin: 0 auto;
        }
        .form-signin input[type="text"], .form-signin input[type="password"], .form-signin input[type="email"] {
            height: 44px;
            font-size: 16px;
            border-radius: 5px;
            margin-bottom: 10px;
            padding: 0 20px;
            border: 1px solid #ccc;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
            transition: border-color .2s;
        }
        .form-signin input:focus {
            border-color: #66afe9;
            outline: 0;
            box-shadow: 0 0 8px rgba(102, 175, 233, 0.6);
        }
        .btn-signin {
            background-color: #5cb85c;
            font-size: 18px;
            font-weight: 700;
            height: 44px;
            border-radius: 5px;
            border: none;
            transition: background-color .3s;
        }
        .btn-signin:hover {
            background-color: #449d44;
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
        .tick-mark {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 80px;
            color: #5cb85c; /* Green color */
            opacity: 0;
            animation: fadeInOut 2s ease-in-out;
        }

        @keyframes fadeInOut {
            0%, 100% {
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar starts -->
    <div class="navbar-wrapper">
        <div class="navbar navbar-inverse navbar-static-top" role="navigation">
            <h2 style="color: white" align="center">Register to Order Food using your ROLL NUMBER</h2>
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html"></a>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="index.php">Home</a></li>
                        <li><a href="customer_login.php">Login</a></li>
                        <li><a href="full_menu.php">Full Menu</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Navigation Bar ends -->

    <!-- Content wrapper starts -->
    <div class="container">
        <h3 class="welcome">Enter your Details</h3>
        <div class="card-container">
            <h4 class="login_title">Register Here</h4>
            <form class="form-signin" method="POST">
                <input type="text" name="c_name" class="form-control" placeholder="Name" required autofocus>
                <input type="email" name="c_email" class="form-control" placeholder="rollnumber@cmrcet.ac.in" required>
                <input type="text" name="c_contact" class="form-control" placeholder="Contact Number" required>
                <input type="text" name="c_prn" class="form-control" placeholder="**H5*A****" required>
                <input type="password" name="c_pass" class="form-control" placeholder="Password" required>
                <input type="password" name="pass" class="form-control" placeholder="Confirm Password" required>
                <button class="btn btn-lg btn-signin" type="submit" name="register">Register</button>
                <?php
                if(!empty($message)) {
                    echo '<p class="error-message">'.$message.'</p>';
                }
                ?>
            </form>
            <!-- Tick mark for successful registration -->
            <div id="tick-mark" class="tick-mark">&#10004;</div>
        </div>
    </div>
    <!-- Content wrapper ends -->

    <script>
        // Check if registration success session is set
        <?php
        if (isset($_SESSION['registration_success']) && $_SESSION['registration_success']) {
            echo '$(document).ready(function() {
                $("#tick-mark").css("display", "block");
            });';
            // Unset the session variable after showing tick mark
            unset($_SESSION['registration_success']);
        }
        ?>
    </script>
</body>
</html>
