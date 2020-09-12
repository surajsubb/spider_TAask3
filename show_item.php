<html>
<head>
<link rel="stylesheet" href="show_item.css"> 
</head>
<?php 
    session_start();
    $servername = $_SESSION['servername'];
    $susername = $_SESSION['server_username'];
    $spassword = $_SESSION['password'];
    $dbname = $_SESSION['dbname'];
    $empty = "";
    // Create connection
    $conn = new mysqli($servername, $susername, $spassword, $dbname);
  
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error); 
    }
    else{
      
        $sql = $_SESSION['sql'];
        $Result = $conn->query($sql);
        if(mysqli_num_rows($Result) == 0){
          $_SESSION['empty'] = "No items";
        }
        if (($_SERVER["REQUEST_METHOD"] == "POST")&&(!isset($_POST['order_by']))){

          $array = array_keys($_POST);

          if($_SESSION['user_type'] == "seller"){
            $update = str_replace('_',' ',$array[0]);
            $_SESSION['to_update'] = $update;
            $_SESSION['update'] = true;
            header("location: update_item_seller.php");
          }
          elseif($_SESSION['cart']==true){
            $to_buy = str_replace('_',' ',$array[0]);
            $_SESSION['to_buy'] = $to_buy;
            $_SESSION['cart'] = false;
            $_SESSION['add_to_cart'] = true;
            header("location: buy_item_buyer.php");
          }
          elseif($_SESSION['add_to_cart']!=true){
            $add_cart = str_replace('_',' ',$array[0]);
            $_SESSION['add_cart'] = $add_cart;
            $_SESSION['add_to_cart'] = true;
            header("location: add_cart_buyer.php");
          }
        }
        ?>
        <?php
        while($row = $Result->fetch_assoc()){
          $item_image = $row['item_image'];
          $item_name = $row['itemname'];
          $item_desc = $row['itemdesc'];
          $item_price = $row['price'];
          if($_SESSION['cart'] == true){
            $item_quantity = $_SESSION['cart_quantity'];
          }
          else{
            $item_quantity = $row['quantity'];
          }
          $item_seller = $row['seller'];
        ?>
        <div id="item_container">
          <img class="item_info" src = <?php echo $item_image;?>>
          <p class="item_info">Item: <?php echo $item_name;?></p>
          <div class="tooltip item_info">Description:
            <span class="tooltiptext"><?php echo $item_desc;?></span>
          </div>
          <p class="item_info">Price: ₹<?php echo $item_price;?></p>
          <p class="item_info"><?php if($_SESSION['cart']==true){echo "Quantity:";} else{echo "Stock left:";}?> <?php echo $item_quantity;?></p>
          <?php
          if($_SESSION['user_type']!="seller"){
          ?>
            <p class="item_info">Seller: <?php echo $item_seller;?></p>
          <?php
          }
          ?>
          <?php
          if($_SESSION['user_type']=="seller"){
          ?>
            <form method = "post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
              <input type="submit" name = <?php echo str_replace(' ','_',$item_name) ?> value = "Update">
            </form>
          <?php
          }
          elseif($_SESSION['cart']==true){
            ?>
              <form method = "post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <input type="submit" name = <?php echo str_replace(' ','_',$item_name) ?> value = "Buy">
              </form>
            <?php
          }
          elseif($_SESSION['add_to_cart']!=true){
          ?>
              <form method = "post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <input type="submit" name = <?php echo str_replace(' ','_',$item_name) ?> value = "Add to Cart">
              </form>
          <?php
          }
          ?>

        </div>
        <?php
      }
    }
?>
</html>