<?php
session_start();
ob_start(); // Start output buffering

include("includes/db.php");

// Include PHPMailer classes
require 'includes/PHPMailer/src/Exception.php';
require 'includes/PHPMailer/src/PHPMailer.php';
require 'includes/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['submit'])) {
    // Sanitize user input
    $c_prn = filter_input(INPUT_POST, 'prn', FILTER_SANITIZE_STRING);

    // Prepare and execute the query to check if the Roll Number exists
    $stmt = $con->prepare("SELECT * FROM customers WHERE customer_prn = ?");
    $stmt->bind_param("s", $c_prn);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Fetch customer details
        $row_customer = $result->fetch_assoc();
        // Generate OTP and store it in session
        $otp = mt_rand(100000, 999999); // Generate a random 6-digit OTP
        $_SESSION['otp'] = $otp;
        $_SESSION['prn'] = $c_prn;

        // Send OTP to customer's email (assuming it's stored in the database)
        $customer_email = $row_customer['customer_email'];
        $subject = "OTP for Password Reset";
        $message = "Your OTP for password reset: $otp";

        // Create an instance of PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->SMTPDebug = 0; // Disable verbose debug output
            $mail->isSMTP(); // Send using SMTP
            $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
            $mail->SMTPAuth   = true; // Enable SMTP authentication
            $mail->Username   = 'foodflex23@gmail.com'; // Replace with your Gmail address
            $mail->Password   = 'eevt ijju mbci huhs'; // Replace with your Gmail password or app-specific password
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = 587; // TCP port to connect to

            // Recipients
            $mail->setFrom('foodflex23@gmail.com', 'foodflex'); // Replace with your email address and name
            $mail->addAddress($customer_email); // Add a recipient

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
            // Redirect to OTP verification page
            header("Location: verify_otp.php");
            exit();
        } catch (Exception $e) {
            echo "<script>alert('Failed to send OTP. Error: {$mail->ErrorInfo}')</script>";
        }
    } else {
        echo "<script>alert('Roll Number not found in our database. Please try again.')</script>";
    }

    $stmt->close();
}

ob_end_flush(); // End output buffering and flush output
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <!-- Include necessary CSS and JS files -->
    <link rel="stylesheet" href="styles/ss.css">
</head>
<body>
    <div class="container">
        <h3 class="welcome text-center" align="center" style="color: black">Forgot Password</h3>
        <div class="card card-container">
            <form class="form-signin" method="POST">
                <p class="input_title">Enter your Roll Number</p>
                <input type="text" name="prn" class="login_box" placeholder="**H5*A****" required autofocus>
                <center><input class="btn btn-lg btn-info" type="submit" name="submit" style="color: white" value="Submit"></center>
            </form>
        </div>
    </div>
</body>
</html>
