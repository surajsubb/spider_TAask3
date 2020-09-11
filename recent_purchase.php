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
        $username = $_SESSION['username'];
        $sql = $_SESSION['recent_sql'];
        $result1 = $conn->query($sql);
        echo $conn->error;
        while($row = $result1->fetch_assoc()){
            $itemname=$row['itemname'];
            $item_quantity=$row['order_size'];
            $seller=$row['seller_id'];
            $purchase_date = $row['order_date'];
            $purchase_date = strtotime($purchase_date);
            $sql = "SELECT * FROM Items WHERE itemname = '$itemname'"; 
            $Result = $conn->query($sql);
            while($row = $Result->fetch_assoc()){
                $item_image = $row['item_image'];
                $item_name = $row['itemname'];
                $item_desc = $row['itemdesc'];
                $item_price = $row['price'];
                $total=$item_quantity*$item_price;
                ?>
                <div id="item_container">
                <img class="item_info" src = <?php echo $item_image;?>>
                <p class="item_info">Item: <?php echo $item_name;?></p>
                <div class="tooltip item_info">Description:
                    <span class="tooltiptext"><?php echo $item_desc;?></span>
                </div>
                <p class="item_info">Price per item: ₹<?php echo $item_price;?></p>
                <p class="item_info">Quantity: <?php echo $item_quantity;?></p>
                <p class="item_info">Total Price: <?php echo $total;?></p>
                <p class="item_info">Seller: <?php echo $seller;?></p>
                <p class="item_info">Purchased on: <?php echo date("m-d-Y", $purchase_date);?></p>
                </div>
        <?php
            }
        }
        ?>
    </body>
</html>
<?php
 /*if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $sql = "INSERT INTO Orders (order_size,customer_id,seller_id,itemname)
            VALUES ('$item_quantity','$username','$seller','$itemname')";
            $conn->query($sql);
            echo $conn->error;
            $sql = "DELETE FROM Cart WHERE itemname = '$itemname' AND customer_id = '$username'";
            $conn->query($sql);
            echo $conn->error;
        }*/
?>