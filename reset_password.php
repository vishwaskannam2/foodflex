<?php
session_start();
ob_start(); // Start output buffering

include("includes/db.php");

// Check for OTP and PRN in the session
if (!isset($_SESSION['otp']) || !isset($_SESSION['prn'])) {
    header("Location: forgot_password.php");
    exit();
}

if (isset($_POST['reset'])) {
    // Sanitize user input
    $new_pass = filter_input(INPUT_POST, 'new_pass', FILTER_SANITIZE_STRING);
    $confirm_pass = filter_input(INPUT_POST, 'confirm_pass', FILTER_SANITIZE_STRING);

    // Validate password length
    if (strlen($new_pass) < 8) {
        echo "<script>alert('Password must be at least 8 characters long.')</script>";
    } else if ($new_pass === $confirm_pass) {
        $c_prn = $_SESSION['prn'];

        // Hash the new password
        $hashed_pass = password_hash($new_pass, PASSWORD_BCRYPT);

        // Use prepared statement to update password
        $update_pass_query = "UPDATE customers SET customer_pass=? WHERE customer_prn=?";
        if ($stmt = $con->prepare($update_pass_query)) {
            $stmt->bind_param("ss", $hashed_pass, $c_prn);
            if ($stmt->execute()) {
                // Password updated successfully
                echo "<script>alert('Password updated successfully! Please login with your new password.')</script>";
                header("Location: index.php");
                exit();
            } else {
                // Error updating password
                echo "<script>alert('Failed to update password. Please try again.')</script>";
                echo "MySQL Error: " . $stmt->error; // Debugging: Output MySQL error if any
            }
            $stmt->close();
        } else {
            // Error preparing statement
            echo "<script>alert('Failed to prepare statement.')</script>";
        }
    } else {
        // Passwords do not match
        echo "<script>alert('Passwords do not match. Please try again.')</script>";
    }
}

ob_end_flush(); // End output buffering and flush output
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <!-- Include necessary CSS and JS files -->
    <link rel="stylesheet" href="styles/ss.css">
</head>
<body>
    <div class="container">
        <h3 class="welcome text-center" align="center" style="color: black">Reset Password</h3>
        <div class="card card-container">
            <form class="form-signin" method="POST">
                <p class="input_title">Enter new password</p>
                <input type="password" name="new_pass" class="login_box" placeholder="Enter new password" required minlength="8">
                <p class="input_title">Confirm new password</p>
                <input type="password" name="confirm_pass" class="login_box" placeholder="Confirm new password" required minlength="8">
                <center><input class="btn btn-lg btn-info" type="submit" name="reset" style="color: white" value="Reset Password"></center>
            </form>
        </div>
    </div>
</body>
</html>
