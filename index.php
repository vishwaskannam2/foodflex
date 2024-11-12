<?php
session_start();
if (!isset($_SESSION['customer_email'])) {
    header("location: customer_login.php");
    exit();
}
include("includes/db.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Food Flex</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            padding-top: 50px;
        }

        .carousel-inner > .item > img,
        .carousel-inner > .item > a > img {
            width: 100%;
            height: auto;
            max-height: 400px;
        }

        .itemsTitle {
            margin-top: 20px;
            font-size: 2.5rem;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
            background-color: #343a40;
            padding: 10px 40px;
            border-radius: 5px;
            text-align: center;
        }

        .special-img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 50%;
        }

        .special-item {
            text-align: center;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px;
        }

        .special-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background-color: #337ab7;
            border-color: #2e6da4;
            color: #fff;
            text-transform: uppercase;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #286090;
            border-color: #204d74;
        }

        .footer {
            background-color: #343a40;
            color: #ffffff;
            padding: 2px 0;
            text-align: center;
            border-top: 1px solid #dee2e6;
            width: 100%;
            position: relative;
            bottom: 0;
        }

        .footer p {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="navbar-wrapper">
        <nav class="navbar navbar-static-top navbar-inverse" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php">Food Flex</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="index.php">Home</a></li>
                        <li><a href="<?php echo isset($_SESSION['customer_email']) ? 'logout.php' : 'customer_login.php'; ?>">
                                <?php echo isset($_SESSION['customer_email']) ? 'Sign Out' : 'Sign In'; ?></a></li>
                        <li><a href="full_menu.php">Full Menu</a></li>
                        <li><a href="customer/my_account.php">Profile</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <!-- Carousel for Specials -->
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
        </ol>
        <div class="carousel-inner">
            <div class="item active">
                <img src="images/canteen13.png" alt="college" class="img-responsive">
            </div>
            <div class="item">
                <img src="images/canteen2.png" alt="canteen" class="img-responsive">
            </div>
        </div>
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <!-- Section for Specials -->
    <div class="container">
        <h2 class="itemsTitle">SPECIALS</h2>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="special-item">
                    <img class="special-img" src="images/masaladosa.png" alt="MASALA DOSA">
                    <h3>MASALA DOSA</h3>
                    <p><a class="btn btn-primary" href="full_menu.php">&#8377; 30 ADD TO CART &raquo;</a></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="special-item">
                    <img class="special-img" src="images/sandwich.png" alt="SANDWICH">
                    <h3>SANDWICH</h3>
                    <p><a class="btn btn-primary" href="full_menu.php">&#8377; 30 ADD TO CART &raquo;</a></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="special-item">
                    <img class="special-img" src="images/oreo_3.png" alt="MILKSHAKES">
                    <h3>MILKSHAKES</h3>
                    <p><a class="btn btn-primary" href="full_menu.php">&#8377; 30 ADD TO CART &raquo;</a></p>
                </div>
            </div>
        </div>
    </div>
    

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 Food Flex, All rights reserved</p>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>

</html>
