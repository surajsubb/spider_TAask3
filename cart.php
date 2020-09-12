<?php
session_start();
if(!isset($_SESSION['user_type']) || $_SESSION['user_type']!="buyer"){
    header("location: login.php");
}
$_SESSION['cart']=false;
?>
<!DOCTYPE HTML>  
<html>
    <head>
        <link rel="stylesheet" href="dashboard_buyer.css"> 
        <link rel="stylesheet" href="cart.css"> 
    </head>
    <h1>Your Cart</h1> 
    <div id = "purchases">
       
        <?php
            $username = $_SESSION['username'];
            $servername = $_SESSION['servername'];
            $susername = $_SESSION['server_username'];
            $spassword = $_SESSION['password'];
            $dbname = $_SESSION['dbname'];
            $conn = new mysqli($servername, $susername, $spassword, $dbname);
            
            $empty = "";
            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error); 
            }
            $counter = 1;
            $sql = "SELECT * FROM Cart WHERE customer_id = '$username'";
            $result = $conn->query($sql);
            
            if(mysqli_num_rows($result) == 0){
                $empty = "No items in cart";
            }
            $_SESSION['cart']=true;
           while($row = $result->fetch_assoc()){
                $itemname = $row['itemname'];
                $customer_id = $row['customer_id'];
                if($customer_id == $username){
                    $_SESSION['cart_quantity'] = $row['quantity'];
                    $_SESSION['sql'] = "SELECT * FROM Items WHERE itemname = '$itemname'";
                    include 'show_item.php';
                }
            }
            
        ?>
        <p><?php echo $empty ?>
        <h3><a href="dashboard_buyer.php">Back to Dashboard</a></h3>
    <div>
</html>