<?php
session_start();
include("includes/db.php");

// Check if the customer is logged in
if (!isset($_SESSION['customer_email'])) {
    header("location: customer_login.php");
    exit();
}

$user = $_SESSION['customer_email'];

// Handle adding product to cart
if (isset($_GET['product_id'])) {
    $id = $_GET['product_id'];

    // Get product details
    $query = "SELECT * FROM products WHERE product_id=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $runQuery = $stmt->get_result();
    $row = $runQuery->fetch_assoc();

    if ($row) {
        $productName = $row['product_title'];
        $price = $row['product_price'];
        $quantityAvailable = $row['quantity'];

        // Check if product quantity is sufficient
        if ($quantityAvailable > 0) {
            // Check if the product is already in the cart
            $checkQuery = "SELECT * FROM orders WHERE p_id=? AND customer_prn=?";
            $stmt = $con->prepare($checkQuery);
            $stmt->bind_param('ss', $id, $user);
            $stmt->execute();
            $checkResult = $stmt->get_result();

            if ($checkResult->num_rows > 0) {
                // Update the quantity if the product is already in the cart
                $updateQuery = "UPDATE orders SET quantity = quantity + 1 WHERE p_id=? AND customer_prn=?";
                $stmt = $con->prepare($updateQuery);
                $stmt->bind_param('ss', $id, $user);
                $stmt->execute();
            } else {
                // Insert into orders table if not already in the cart
                $insertQuery = "INSERT INTO orders (p_id, p_name, p_price, customer_prn, quantity) VALUES (?, ?, ?, ?, 1)";
                $stmt = $con->prepare($insertQuery);
                $stmt->bind_param('isds', $id, $productName, $price, $user);
                $insertResult = $stmt->execute();

                if (!$insertResult) {
                    echo "Error: " . $con->error;
                }
            }

            // Decrease the quantity of the product
            $updateQuantityQuery = "UPDATE products SET quantity = quantity - 1 WHERE product_id = ?";
            $stmt = $con->prepare($updateQuantityQuery);
            $stmt->bind_param('s', $id);
            $stmt->execute();

            // Redirect to avoid duplicate form submissions and update cart amount
            header("Location: full_menu.php");
            exit();
        } else {
            echo "Product out of stock";
        }
    } else {
        echo "Product not found";
    }
}

// Handle removing product from cart
if (isset($_POST['remove_product_id'])) {
    $removeProductId = $_POST['remove_product_id'];

    // Get the quantity of the product to be removed
    $quantityQuery = "SELECT quantity FROM orders WHERE p_id=? AND customer_prn=?";
    $stmt = $con->prepare($quantityQuery);
    $stmt->bind_param('ss', $removeProductId, $user);
    $stmt->execute();
    $quantityResult = $stmt->get_result();
    $quantityRow = $quantityResult->fetch_assoc();
    $quantityToRemove = $quantityRow['quantity'];

    // Remove item from orders table
    $deleteQuery = "DELETE FROM orders WHERE p_id=? AND customer_prn=?";
    $stmt = $con->prepare($deleteQuery);
    $stmt->bind_param('ss', $removeProductId, $user);
    $stmt->execute();

    // Increase the quantity of the removed product
    $updateQuantityQuery = "UPDATE products SET quantity = quantity + ? WHERE product_id = ?";
    $stmt = $con->prepare($updateQuantityQuery);
    $stmt->bind_param('is', $quantityToRemove, $removeProductId);
    $stmt->execute();

    // Redirect to avoid duplicate form submissions and update cart amount
    header("Location: full_menu.php");
    exit();
}

// Fetch current cart total
$query4 = "SELECT SUM(p_price * quantity) AS total FROM orders WHERE customer_prn=?";
$stmt = $con->prepare($query4);
$stmt->bind_param('s', $user);
$stmt->execute();
$runQuery4 = $stmt->get_result();
$row = $runQuery4->fetch_assoc();
$sum = $row['total'] ?? 0;

// Fetch items in the cart for display
$cartItemsQuery = "SELECT * FROM orders WHERE customer_prn=?";
$stmt = $con->prepare($cartItemsQuery);
$stmt->bind_param('s', $user);
$stmt->execute();
$cartItemsResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Full Menu</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f5f5f5;
            text-align: center;
            padding-top: 50px;
        }
        .table-container {
            margin-bottom: 30px;
        }
        .cart-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-remove {
            color: black;
            cursor: pointer;
            border: none;
            background: none;
        }
        .btn-remove:hover {
            color: black;
            text-decoration: underline;
        }
        .empty-cart-msg {
            color: red;
            font-weight: bold;
        }
        .navbar {
            margin-bottom: 0;
        }
        .navbar-brand {
            font-size: 24px;
        }
        .navbar-nav > li > a {
            font-size: 16px;
        }
        @media (max-width: 767px) {
            .navbar-brand {
                font-size: 20px;
            }
            .navbar-nav > li > a {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Food Flex</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="nav navbar-nav">
                    <li><a href="full_menu.php">Full Menu</a></li>
                    <li><a href="customer/my_account.php">Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="table-container">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Add to Cart</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    $query = "SELECT * FROM products";
                    $runQuery = mysqli_query($con, $query);
                    while ($row = mysqli_fetch_assoc($runQuery)) {
                        $productName = $row['product_title'];
                        $price = $row['product_price'];
                        $id = $row['product_id'];
                        $q = $row['quantity'];
                        $i++;
                        echo '
                        <tr>
                            <td>'.$i.'</td>
                            <td>'.$productName.'</td>
                            <td>₹'.$price.'</td>
                            <td>'.$q.'</td>
                            <td><a href="full_menu.php?product_id='.$id.'" class="btn btn-primary btn-sm">Add to Cart</a></td>
                        </tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Cart box to display items and total -->
        <?php if ($cartItemsResult->num_rows > 0): ?>
            <div class="cart-box">
                <h4>Your Cart</h4>
                <?php while ($cartItem = $cartItemsResult->fetch_assoc()): ?>
                    <div>
                        <strong><?php echo htmlspecialchars($cartItem['p_name']); ?></strong>
                        - ₹<?php echo htmlspecialchars($cartItem['p_price']); ?> x <?php echo htmlspecialchars($cartItem['quantity']); ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="remove_product_id" value="<?php echo htmlspecialchars($cartItem['p_id']); ?>">
                            <button type="submit" class="btn btn-sm btn-danger btn-remove">Remove</button>
                        </form>
                    </div>
                <?php endwhile; ?>
                <hr>
                <p>Total: ₹<?php echo $sum; ?></p>
                <form method="POST" action="formpro.php">
                    <input type="submit" class="btn btn-success btn-sm" value="FINALIZE ORDER" name="sub">
                    <input type="hidden" name="hid" value="<?php echo $sum; ?>">
                </form>
            </div>
        <?php else: ?>
            <div class="empty-cart-msg">
                Your cart is empty.
            </div>
        <?php endif; ?>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
