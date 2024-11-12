<?php
session_start();
// Check if OTP session variable is set
if(!isset($_SESSION['otp']) || !isset($_SESSION['prn'])) {
    header("Location: forgot_password.php"); // Redirect if no OTP or PRN session is found
    exit();
}

if(isset($_POST['verify'])) {
    $user_otp = $_POST['otp'];

    // Check if entered OTP matches the session OTP
    if($user_otp == $_SESSION['otp']) {
        // Redirect to password reset form
        header("Location: reset_password.php");
        exit();
    } else {
        echo "<script>alert('Incorrect OTP. Please try again.')</script>";
    }
}
?>

<html>
<head>
    <title>Verify OTP</title>
    <!-- Include necessary CSS and JS files -->
    <link rel="stylesheet" href="styles/ss.css">
</head>
<body>
    <div class="container">
        <h3 class="welcome text-center" align="center" style="color: black">Verify OTP</h3>
        <div class="card card-container">
            <form class="form-signin" method="POST">
                <p class="input_title">Enter OTP received on your registered email</p>
                <input type="text" name="otp" class="login_box" placeholder="Enter OTP" required autofocus>
                <center><input class="btn btn-lg btn-info" type="submit" name="verify" style="color: white" value="Verify"></center>
            </form>
        </div>
    </div>
</body>
</html>