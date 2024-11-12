<?php 
include("includes/db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Amount</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            max-width: 795px;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: black;
            color: white;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid white;
        }
        th {
            background-color: white;
            color: black;
        }
        tr:nth-child(even) {
            background-color: #333;
        }
        tr:hover {
            background-color: #555;
        }
        input[type="text"], select {
            width: calc(100% - 12px);
            padding: 8px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-align: center;
            display: inline-block;
        }
    </style>
</head>
<body>
    <form action="add_amount.php" method="post" enctype="multipart/form-data"> 
        <table align="center" width="795" border="2">
            <tr align="center">
                <td colspan="7"><h2>ADD AMOUNT</h2></td>
            </tr>
            <tr>
                <td align="right"><b>Choose Customer:</b></td>
                <td>
                    <select name="c_prn">
                        <option>Customer Roll</option>
                        <?php 
                        // Updated query to fetch customers sorted by customer_prn
                        $get_cats = "SELECT * FROM customers ORDER BY customer_prn";
                        $run_cats = mysqli_query($con, $get_cats);
                        
                        while ($row_cats = mysqli_fetch_assoc($run_cats)) {
                            $cust_prn = $row_cats['customer_prn']; 
                            echo "<option value='$cust_prn'>$cust_prn</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right"><b>Amount:</b></td>
                <td><input type="text" name="amount" required/></td>
            </tr>
            <tr align="center">
                <td colspan="7"><input type="submit" name="add" value="ADD AMOUNT NOW"/></td>
            </tr>
        </table>
    </form>

    <?php 
    if(isset($_POST['add'])) {
        //getting the text data from the fields
        $c_prn = $_POST['c_prn'];
        $amt = $_POST['amount'];
        
        // Validate if amount is numeric
        if(!is_numeric($amt)) {
            echo "<script>alert('Enter a numerical amount')</script>";
            echo "<script>window.open('index.php?add_amount','_self')</script>";
        } else {
            // Update customer amount
            $insert_amount = "UPDATE customers SET amount = amount + '$amt' WHERE customer_prn = '$c_prn'";
            $insert_pro = mysqli_query($con, $insert_amount);
            
            if($insert_pro) {
                echo "<script>alert('Amount has been updated')</script>";
                echo "<script>window.open('index.php?add_amount','_self')</script>";
            }
        }
    }
    ?>
</body>
</html>
