<html>
<head>
<link rel="stylesheet" href="show_item.css"> 
</head>
<?php 
    $servername = "localhost";
    $susername = "spider";
    $spassword = "random";
    $dbname = "Store";

    // Create connection
            $conn = new mysqli($servername, $susername, $spassword, $dbname);

            // Check connection
            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error); 
            }
            else{
                $sql = "select * from Items where seller = 'kevin'";
                $result = $conn->query($sql);
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
                  <p class="item_info">Seller: <?php echo $item_seller;?></p>
                  </div>
                <?php
                }
            }
?>

</html>