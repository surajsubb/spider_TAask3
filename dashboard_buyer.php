<?php
session_start();
if(!isset($_SESSION['user_type']) || $_SESSION['user_type']!="buyer"){
    header("location: login.php");
}
?>
<!DOCTYPE HTML>  
<html>
    <head>
        <link rel="stylesheet" href="dashboard_buyer.css"> 
    </head>
    <h1>Welcome <?php echo $_SESSION['username'];?></h1> 
    <div id="dashboard_buttons">
        <a href="add_item_seller.php">add item </a>
        <a href="add_item_seller.php">add item </a>
    </div>
    <div id = "purchases">
        <p>Recent purchases:</p>
        <br>
        <?php
            $username = $_SESSION['username'];
            $_SESSION['sql'] = "select * from Items where seller = 'kevin'";
            include 'show_item.php';
        ?>
    <div>
</html>