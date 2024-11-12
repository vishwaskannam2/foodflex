<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inserting Product</title>
    <script src="//cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({selector:'textarea'});
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        form {
            margin: 20px auto;
            padding: 20px;
            width: 80%;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        input[type="text"], select {
            width: calc(100% - 20px);
            padding: 8px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            float: right;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php 
    include("includes/db.php");
    ?>
    
    <form action="insert_product.php" method="post" enctype="multipart/form-data"> 
        <table>
            <tr>
                <td colspan="2"><h2>Insert New Product</h2></td>
            </tr>
            <tr>
                <td align="right"><b>Product Title:</b></td>
                <td><input type="text" name="product_title" size="60" required/></td>
            </tr>
            <tr>
                <td align="right"><b>Product Price:</b></td>
                <td><input type="text" name="product_price" required/></td>
            </tr>
            <tr>
                <td align="right"><b>Quantity (Number of Plates):</b></td>
                <td><input type="text" name="quantity" required/></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="insert_post" value="Insert Product Now"/></td>
            </tr>
        </table>
    </form>
    
    <?php 
    if(isset($_POST['insert_post'])){
        // Print the entire $_POST array for debugging
        echo '<pre>';
        print_r($_POST);
        echo '</pre>';

        //getting the text data from the fields
        $product_title = $_POST['product_title'];
        $product_price = $_POST['product_price'];
        $quantity = $_POST['quantity'] ?? '';

        // Check if quantity is set and not empty
        if (!empty($quantity)) {
            // Proceed with insertion
            $insert_product = "INSERT INTO products (product_title, product_price, quantity, product_desc, product_keywords) VALUES ('$product_title', '$product_price', '$quantity', '', '')";
            
            $insert_pro = mysqli_query($con, $insert_product);
            
            if($insert_pro){
                echo "<script>alert('Product Has been inserted!')</script>";
                echo "<script>window.open('index.php?insert_product','_self')</script>";
            }
        } else {
            // Handle the case where quantity is empty
            echo "<p class='error-message'>Please provide a valid quantity!</p>";
        }
    }
    ?>
</body> 
</html>
