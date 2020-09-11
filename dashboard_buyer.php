<?php
session_start();
if(!isset($_SESSION['user_type']) || $_SESSION['user_type']!="buyer"){
    header("location: login.php");
}
$_SESSION['add_to_cart']=false;
$_SESSION['cart']=false;
?>
<!DOCTYPE HTML>  
<html>
    <style>
    .error {color: #FF0000;}
    </style>
    <?php
        if(isset($_SESSION['from_search'])&&($_SESSION['from_search']==true)){
            $_SESSION['from_search']=false; 
        }
        else{
            $_SESSION['sql'] = "SELECT * FROM Items";
        }
        $_SESSION['order'] = true;
        $selected = "name";
        if (($_SERVER["REQUEST_METHOD"] == "POST")&&($_SESSION['order']==true)){
            if(isset($_POST['order_by'])){
                if(!empty($_POST['order'])){
                    if($_POST['order'] == "name"){
                        $order_by = "itemname";
                        $selected = "name";
                    }
                    elseif($_POST['order'] == "stock"){
                        $order_by = "quantity";
                        $selected = "stock";
                    }
                    elseif($_POST['order'] == "price"){
                        $order_by = "price";
                        $selected = "price";
                    }
                    else{
                        $order_by = "seller";
                        $selected = "seller";
                    }
                    $_SESSION['sql'] = $_SESSION['sql']." ORDER BY $order_by";
                }
            }
        }
    ?>
    <head>
        <link rel="stylesheet" href="dashboard_buyer.css"> 
    </head>
    <h1>Welcome <?php echo $_SESSION['username'];?></h1> 
    <div id="dashboard_buttons">
        <a id="cart"href="cart.php">Cart </a>
        <a id="order_history"href="order_history.php">Order History </a>
        <a id="logout" href="logout.php">Logout </a>
    </div>
    <div id = "container">
        <div>
        <h3 id = "product">All Products:</h3>
        </div>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <select name="order" id="order_by">
                <option value="name">Name</option>
                <option value="stock">Stock</option>
                <option value="price">Price</option>
                <option value="seller">Seller</option>
            </select>
            <input id="go" type="submit" name = "order_by" value="Go">
        </form>
        <div id="search">
            <form method="post" action="search.php">
                <input id="search_text" name="search_text" type="text" placeholder="<?php print($_SESSION['searchErr']);?>">
                <input id="serach_button" type="submit" value="Search">
            </form>
        </div>
        <div id = "products">
        <?php
            $username = $_SESSION['username'];
            include 'show_item.php';
        ?>
        </div>
    </div>
    <div id = "recent">
        <div>
        <h3 id="product">Recently Purchased:</h3>
        </div>
        <div id = "purchased">
        <?php
            $_SESSION['recent_sql'] = "SELECT * FROM Orders WHERE customer_id = '$username' ORDER BY order_date DESC LIMIT 3 ";
            include 'recent_purchase.php';
        ?>
        </div>
    </div>
</html>