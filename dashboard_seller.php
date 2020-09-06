<?php
session_start();
if(!isset($_SESSION['user_type']) || $_SESSION['user_type']!="seller"){
    header("location: login.php");
}
?>
<!DOCTYPE HTML>  
<html>
    <head>
        <link rel="stylesheet" href="/dashboard_seller.css"> 
    </head>
    <h1>Welcome <?php echo $_SESSION['username'];?></h1>  
    add item <a href="add_item_seller.php">here</a>
    <div id = "added_items">
        <p>Items added by you:</p>
        <br>
        <?php
            include 'show_item.php';
        ?>
    <div>
</html>