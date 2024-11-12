<?php
session_start();
include("includes/db.php");

$message = ""; // Variable to store error message

if (isset($_POST['login'])) {
    $c_prn = $_POST['prn'];
    $c_pass = $_POST['pass'];

    // Use prepared statements to prevent SQL injection
    $sel_c = "SELECT * FROM customers WHERE customer_prn=?";
    $stmt = mysqli_prepare($con, $sel_c);
    mysqli_stmt_bind_param($stmt, "s", $c_prn);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        // Verify the password
        if (password_verify($c_pass, $row['customer_pass'])) {
            $_SESSION['customer_email'] = $c_prn;
            header("Location: index.php");
            exit();
        } else {
            $message = "Roll number or password is incorrect, please try again!";
        }
    } else {
        $message = "Roll number or password is incorrect, please try again!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles/style.css">
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
        .form-signin input[type="text"], .form-signin input[type="password"] {
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
        .forgot-password, .btn-info, .btn-success {
            margin-top: 10px;
        }
        .forgot-password a, .btn-success a {
            text-decoration: none;
            color: white;
        }
        .btn-info:hover, .btn-success:hover {
            background-color: #5bc0de;
        }
        .error-message {
            color: red;
            margin-top: 10px;
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
            <h2 style="color: white" align="center">FOOD FLEX</h2>
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
                        <li><a href="full_menu.php">Full Menu</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Navigation Bar ends -->
    <div class="container">
        <h3 class="welcome" align="center">Welcome to the Canteen of CMRCET</h3>
        <div class="card card-container">
            <h4 class="login_title">Login using your Roll Number</h4>
            <form class="form-signin" method="POST">
                <input type="text" name="prn" class="form-control" placeholder="**H5*A****" required autofocus>
                <input type="password" name="pass" class="form-control" placeholder="*****" required>
                <button class="btn btn-lg btn-signin" type="submit" name="login">Login</button>
                <div class="forgot-password">
                    <a href="forgot_password.php"><button type="button" class="btn btn-info">Forgot Password?</button></a>
                </div>
                <?php if (!empty($message)) { ?>
                    <div class="error-message"><?php echo $message; ?></div>
                <?php } ?>
            </form>
        </div>
        <div>
            <a href="customer_register.php"><button type="button" class="btn btn-success">New? Register Here</button></a>
        </div>
    </div>
</body>
</html>
