<?php
session_start();
if(!isset($_SESSION['user_type']) || $_SESSION['user_type']!="seller"){
    header("location: login.php");
}
$_SESSION['update']=false;
$_SESSION['cart']=false;
?>
<!DOCTYPE HTML>  
<html>
    <head>
        <link rel="stylesheet" href="dashboard_seller.css"> 
    </head>
    <h1>Welcome <?php echo $_SESSION['username'];?></h1>  
    <div id="dashboard_buttons">
        <a id="add" href="add_item_seller.php">Add Item </a>
        <a id="order_history" href="order_history_seller.php">Order History</a>
        <a id="logout" href="logout.php">Logout </a>
    </div>
    <div id = "added_items">
        <p>Items added by you:</p>
        <div id="added">
        <?php
            $username = $_SESSION['username'];
            $_SESSION['sql'] = "select * from Items where seller = '$username'";
            include 'show_item.php';
        ?>
        </div>
    </div>
</html>