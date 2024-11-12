<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
        }

        .login {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            animation: fadeInAnimation 0.5s ease forwards;
        }

        @keyframes fadeInAnimation {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        h1 {
            text-align: center;
            color: #333333;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .input-group {
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
            color: #666666;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"],
        input[type="password"] {
            padding: 12px;
            font-size: 16px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            transition: border-color 0.3s ease;
            width: 100%;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #4CAF50;
            outline: none;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 14px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }

        .error-message, .success-message {
            text-align: center;
            margin-top: 10px;
            padding: 10px;
            border-radius: 4px;
        }

        .error-message {
            background-color: #ffcccc;
            color: #cc0000;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
        }

        @media screen and (max-width: 480px) {
            .login {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login">
            <h1>Admin Login</h1>
            <?php echo isset($_GET['logged_out']) ? '<div class="success-message">'.$_GET['logged_out'].'</div>' : ''; ?>
            <form method="post" action="login.php">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary" name="login">Login</button>
            </form>
            <?php
            if(isset($_POST['login'])){
                include("includes/db.php");
                $email = $_POST['email'];
                $pass = $_POST['password'];

                // Using prepared statements to prevent SQL injection
                $stmt = $con->prepare("SELECT * FROM admins WHERE user_email=? AND user_pass=?");
                $stmt->bind_param("ss", $email, $pass);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if($result->num_rows == 1){
                    $_SESSION['user_email'] = $email; 
                    echo "<script>window.open('index.php?logged_in=You have successfully Logged in!','_self')</script>";
                } else {
                    echo "<div class='error-message'>Password or Email is incorrect. Please try again.</div>";
                }

                $stmt->close();
                $con->close();
            }
            ?>
        </div>
    </div>
</body>
</html>
