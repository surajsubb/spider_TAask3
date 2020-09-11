<?php
session_start();
if(!isset($_SESSION['user_type']) || $_SESSION['user_type']!="seller"){
    header("location: login.php");    
}
?>
<!DOCTYPE HTML>  
<html>
    <head>
    <style>
    </style>
        <link rel="stylesheet" href="show_item.css"> 
        <link rel="stylesheet" href="order_history_seller.css"> 
    </head>
    <body>
        <h2> Customer Purchase History</h2>
        <table>
            <tr>
                <th>Item Name</th>
                <th>Customer Username</th>
                <th>Customer Name</th>
                <th>Customer Email</th>
                <th> Quantity bought</th>
                <th>Price per Item</th>
                <th>Total Price</th>
                <th>Order Date</th>
            </tr>
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
        $sql = $_SESSION['recent_sql'] = "SELECT * FROM Orders WHERE seller_id = '$username' ORDER BY order_date DESC";
        $result1 = $conn->query($sql);
        echo $conn->error;
        while($row = $result1->fetch_assoc()){
            $itemname=$row['itemname'];
            $item_quantity=$row['order_size'];
            $customer=$row['customer_id'];
            $purchase_date = $row['order_date'];
            $purchase_date = strtotime($purchase_date);
            $sql = "SELECT * FROM User WHERE username = '$customer'"; 
            $Result = $conn->query($sql);
            while($row = $Result->fetch_assoc()){
                $customer_email = $row['email'];
                $customer_name=$row['firstname']." ".$row['lastname'];
            }
            $sql = "SELECT * FROM Items WHERE itemname = '$itemname'"; 
            $Result = $conn->query($sql);
            while($row = $Result->fetch_assoc()){
                $item_image = $row['item_image'];
                $item_name = $row['itemname'];
                $item_desc = $row['itemdesc'];
                $item_price = $row['price'];
                $total=$item_quantity*$item_price;
            }
            ?>
            <tr>
                <td><?php echo $item_name?></td>
                <td><?php echo $customer?></td>
                <td><?php echo $customer_name?></td>
                <td><?php echo $customer_email?></td>
                <td><?php echo $item_quantity?></td>
                <td>₹<?php echo $item_price?></td>
                <td>₹<?php echo $total?></td>
                <td><?php echo date("\D\a\\t\\e\: m-d-Y \,\T\i\m\\e\: H:i:s", $purchase_date);?></td>
            </tr>
        <?php    
        }
        ?>
        </table>
        <h3><a href= <?php echo "dashboard_seller.php"?>>Dashboard</a></h3>
    </body>
</html>
<?php
/*<img class="item_info" src = <?php echo $item_image;?>>
<p class="item_info">Item: <?php echo $item_name;?></p>
<div class="tooltip item_info">Description:
    <span class="tooltiptext"><?php echo $item_desc;?></span>
</div>
<p class="item_info">Price per item: ₹<?php echo $item_price;?></p>
<p class="item_info">Quantity: <?php echo $item_quantity;?></p>
<p class="item_info">Total Price: <?php echo $total;?></p>
<p class="item_info">Seller: <?php echo $seller;?></p>
<p class="item_info">Purchased on: <?php echo date("m-d-Y", $purchase_date);?></p> */
?>