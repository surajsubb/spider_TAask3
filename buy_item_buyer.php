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
    </head>
    <body>
        <?php
         $servername = $_SESSION['servername'];
         $susername = $_SESSION['server_username'];
         $spassword = $_SESSION['password'];
         $dbname = $_SESSION['dbname'];
         $conn = new mysqli($servername, $susername, $spassword, $dbname);
         // Check connection
         if ($conn->connect_error) {
         die("Connection failed: " . $conn->connect_error); 
         }
        $itemname = $_SESSION['to_buy'];
        $username = $_SESSION['username'];
        $sql = "SELECT * FROM Cart WHERE itemname = '$itemname' AND customer_id = '$username'";
        $result1 = $conn->query($sql);
        while($row = $result1->fetch_assoc()){
            $item_quantity=$row['quantity'];
            $seller=$row['seller_id'];
            $sql = "SELECT * FROM Items WHERE itemname = '$itemname'"; 
            $result2 = $conn->query($sql);
            while($row = $result2->fetch_assoc()){
                $item_image=$row['item_image'];
                $item_desc=$row['itemdesc'];
                $item_price=$row['price'];
            }
            $total=$item_quantity*$item_price;
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $sql = "INSERT INTO Orders (order_size,customer_id,seller_id,itemname)
            VALUES ('$item_quantity','$username','$seller','$itemname')";
            $conn->query($sql);
            echo $conn->error;
            $sql = "DELETE FROM Cart WHERE itemname = '$itemname' AND customer_id = '$username'";
            $conn->query($sql);
            echo $conn->error;
            $sucess = "Order placed, you can go back to dashboard now";
        }
        ?>
        <h2>Checkout</h2> 
        <p><?php echo $sucess;?></p>
        <div>
        <div id="item_container">
          <img class="item_info" src = <?php echo $item_image;?>>
          <p class="item_info">Item: <?php echo $itemname;?></p>
          <div class="tooltip item_info">Description:
            <span class="tooltiptext"><?php echo $item_desc;?></span>
          </div>
          <p class="item_info">Price per item: ₹<?php echo $item_price;?></p>
          <p class="item_info">Quantity: <?php echo $item_quantity;?></p>
          <p class="item_info">Total Price: <?php echo $total;?></p>

          <form method = "post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <input type="submit" name = "buy" value = "Confirm Purchase">
          </form>
        </div>
        <h3><a href="dashboard_buyer.php">Dashboard</a></h3>
    </body>
</html>