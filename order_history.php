<?php
session_start();
?>
<!DOCTYPE HTML>  
<html>
    <head>
        <link rel="stylesheet" href="dashboard_buyer.css"> 
    </head>
    <?php
        $username = $_SESSION['username'];
        $type = $_SESSION['user_type'];
        if($_SESSION['user_type'] == "buyer"){
            $_SESSION['recent_sql'] = "SELECT * FROM Orders WHERE customer_id = '$username' ORDER BY order_date DESC";
        }
    ?>
    <div id = "recent">
        <div>
        <h3 id="product">All Purchases:</h3>
        </div>
        <div id = "purchased">
        <?php
            include 'recent_purchase.php';
        ?>
        </div>
        <h3><a href= <?php echo "dashboard_$type.php"?>>Dashboard</a></h3>
    </div>
</html>