<!DOCTYPE html>
<html>
<head>
    <title>Update Product</title>
    <script src="//cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector:'textarea'
        });
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        table {
            width: 800px;
            margin: 50px auto;
            border-collapse: collapse;
            background-color: black;
            color: white;
        }
        th, td {
            padding: 15px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        input[type="text"] {
            width: calc(100% - 30px);
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<?php
include("includes/db.php");

if(isset($_GET['edit_pro'])){
    $get_id = $_GET['edit_pro']; 
    
    $get_pro = "SELECT * FROM products WHERE product_id='$get_id'";
    $run_pro = mysqli_query($con, $get_pro); 
    
    if(mysqli_num_rows($run_pro) > 0) {
        $row_pro = mysqli_fetch_array($run_pro);
        
        $pro_id = $row_pro['product_id'];
        $pro_title = $row_pro['product_title'];
        $pro_price = $row_pro['product_price'];
        $pro_quantity = $row_pro['quantity'];  // Added quantity field
    } else {
        echo "<script>alert('Product not found!')</script>";
        echo "<script>window.open('index.php?view_products','_self')</script>";
    }
}
?>

<form action="" method="post"> 
    <table align="center">
        <tr align="center">
            <td colspan="2"><h2>Edit & Update Product</h2></td>
        </tr>
        
        <tr>
            <td align="right"><b>Product Title:</b></td>
            <td><input type="text" name="product_title" size="60" value="<?php echo $pro_title; ?>" required/></td>
        </tr>
        
        <tr>
            <td align="right"><b>Product Price:</b></td>
            <td><input type="text" name="product_price" value="<?php echo $pro_price; ?>" required/></td>
        </tr>
        
        <tr>
            <td align="right"><b>Product Quantity:</b></td>
            <td><input type="text" name="product_quantity" value="<?php echo $pro_quantity; ?>" required/></td>
        </tr>
        
        <tr align="center">
            <td colspan="2"><input type="submit" name="update_product" value="Update Product"/></td>
        </tr>
    
    </table>
</form>

<?php 
if(isset($_POST['update_product'])){
    $update_id = $pro_id;
    $product_title = $_POST['product_title'];
    $product_price = $_POST['product_price'];
    $product_quantity = $_POST['product_quantity'];  // Added quantity handling
    
    // Update product details in the database
    $update_product = "UPDATE products SET product_title='$product_title', product_price='$product_price', quantity='$product_quantity' WHERE product_id='$update_id'";
    
    $run_product = mysqli_query($con, $update_product);
    
    if($run_product){
        echo "<script>alert('Product has been updated!')</script>";
        echo "<script>window.open('index.php?view_products','_self')</script>";
    } else {
        echo "<script>alert('Failed to update product. Please try again.')</script>";
    }
}
?>

</body>
</html>
