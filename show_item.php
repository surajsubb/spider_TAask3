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


    // Create connection
    $conn = new mysqli($servername, $susername, $spassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error); 
    }
    else{
        $sql = $_SESSION['sql'];
        $result = $conn->query($sql);
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
          $array = array_keys($_POST);

          $update = str_replace('_',' ',$array[0]);
          if($_SESSION['user_type'] == "seller"){
            $_SESSION['to_update'] = $update;
            $_SESSION['update'] = true;
            header("location: update_item_seller.php");
          }
        }
        while($row = $result->fetch_assoc()){
          $item_image = $row['item_image'];
          $item_name = $row['itemname'];
          $item_desc = $row['itemdesc'];
          $item_price = $row['price'];
          $item_quantity = $row['quantity'];
          $item_seller = $row['seller'];
        ?>
          <div id="item_container">
          <img class="item_info" src = <?php echo $item_image;?>>
          <p class="item_info">Item: <?php echo $item_name;?></p>
          <div class="tooltip item_info">Description:
            <span class="tooltiptext"><?php echo $item_desc;?></span>
          </div>
          <p class="item_info">Price: ₹<?php echo $item_price;?></p>
          <p class="item_info">Stock left: <?php echo $item_quantity;?></p>
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
            <form method = "post">
            <input type="submit" name = <?php echo str_replace(' ','_',$item_name) ?> value = "update">
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