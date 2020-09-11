<?php
session_start();
if(!isset($_SESSION['user_type']) || $_SESSION['user_type']!="buyer"){
    header("location: login.php");    
}
?>
<!DOCTYPE HTML>  
<html>
    <head>
    <style>
    .error {color: #FF0000;}
    </style>
        <link rel="stylesheet" href="show_item.css"> 
        <link rel="stylesheet" href="add_cart_buyer.css"> 
    </head>
    <body>
        <?php
            $quantity = "";
            $quantityErr = "";
            $all_right = 0;
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (empty($_POST["quantity"])) {
                    $quantityErr = "quantity is required";
                    $all_right = 1;
                } else {
                    $quantity = test_input($_POST["quantity"]);
                    // check if quantity only contains number
                    if (!preg_match("/^[\d]{1,2}$/",$quantity)) {
                    $quantityErr = "enter valid number";
                    $all_right = 1;
                    }
                }
                if($all_right!=1){
                    $servername = $_SESSION['servername'];
                    $susername = $_SESSION['server_username'];
                    $spassword = $_SESSION['password'];
                    $dbname = $_SESSION['dbname'];
                    $conn = new mysqli($servername, $susername, $spassword, $dbname);
                    // Check connection
                    if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error); 
                    }
                    else{
                        $item = $_SESSION['add_cart'];
                        $sql = "SELECT * FROM Items WHERE itemname = '$item'";
                        $result = $conn->query($sql);
                        while($row = $result->fetch_assoc()){
                            $item_quantity = $row['quantity'];
                            $seller_id = $row['seller'];
                        }
                        if($quantity > $item_quantity){
                            $quantityErr = "Not Enough Stock, choose less amount";  
                            $all_right = 1;
                        }
                        else{
                            $item_quantity=$item_quantity-$quantity;
                            $sucess =  "Item added to cart!";
                            $sql = "UPDATE Items SET quantity = '$item_quantity' WHERE itemname = '$item'";
                            $result = $conn->query($sql);
                            if($result != TRUE){
                                echo $conn->error;
                            }
                            $username = $_SESSION['username'];
                            $sql = "SELECT * FROM Cart WHERE itemname = '$item'";
                            $result = $conn->query($sql);
                            if($row = $result->fetch_assoc()){
                                $q = $row['quantity'];
                                $quantity+=$q; 
                                $sql = "UPDATE Cart SET quantity = '$quantity' WHERE itemname = '$item'";
                            }
                            else{
                                $sql = "INSERT INTO Cart (customer_id,quantity,seller_id,itemname)
                                VALUES ('$username','$quantity','$seller_id','$item')";
                            }
                            $result = $conn->query($sql);

                            if($result != TRUE){
                                echo $conn->error;
                            }
                        }
                    }
                }
            }
            function test_input($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
        ?>
        <h2>Add To Cart</h2> 
        <p><?php echo $sucess;?></p>
        <div id = "add_to_cart">
            <?php
                $itemname = $_SESSION['add_cart'];
                $_SESSION['sql'] = "select * from Items where itemname = '$itemname'";
                include 'show_item.php';
            ?>
            <form method = "post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <label for = "quantity">Quantity:</label><span class="error">* <?php echo $quantityErr;?></span>
                <br><br>
                <input type="number" id="quantity" name="quantity" min='1' value="<?php echo $quantity;?>">
                <br><br>
                <input type="submit" name = "add" value = "Add to Cart">
            </form>
            <h3><a href="cart.php">Proceed to cart</a></h3>
            <h3><a href="dashboard_buyer.php">Dashboard</a></h3>
        <div>
    </body>
</html>