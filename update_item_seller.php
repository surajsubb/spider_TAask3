<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="update_item_seller.css"> 
<style>
.error {color: #FF0000;}
</style>
</head>
<body>
<?php
  session_start();
  // define variables and set to empty values
  $item_nameErr = $descriptionErr = $priceErr = $quantityErr = $imageErr = "";
  $item_name = $description = $price = $quantity = $image = $image_uploaded = "";
  $all_right = 0;
  $login_now = "";
  $submit_button = "Update";
  $_SESSION['logged_in'] = false;
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["item_name"])) {
      $item_nameErr = "Item name is required";
      $all_right = 1;
    } else {
      $item_name = test_input($_POST["item_name"]);
      // check if name only contains letters and whitespace
      if (!preg_match("/^[a-zA-Z ]*$/",$item_name)) {
        $item_nameErr = "Only letters allowed";
        $all_right = 1;
      }
    }
    if (empty($_POST["description"])) {
      $descriptionErr = "description is required";
      $all_right = 1;
    } else {
      $description = test_input($_POST["description"]);
      // check if name only contains letters and whitespace
      if (!preg_match("/^[\w*.!@#$%^&(){} [\]:;<>,.?\/~+\-=\\\\|]{1,200}$/",$description)) {
        $descriptionErr = "Only letters allowed";
        $all_right = 1;
      }
    }
    if (empty($_POST["price"])) {
      $priceErr = "price is required";
      $all_right = 1;
    } else {
      $price = test_input($_POST["price"]);
      // check if e-mail address is well-formed
      if (!preg_match("/^[\d]+[.]{0,1}[\d]+$/",$price)) {
        $priceErr = "Invalid price";
        $all_right = 1;
      }
    }  
    if (empty($_POST["quantity"])) {
      $quantityErr = "quantity is required";
      $all_right = 1;
    } else {
      $quantity = test_input($_POST["quantity"]);
      // check if quantity only contains number
      if (!preg_match("/^[\d]{1,2}$/",$quantity)) {
        $quantityErr = "only letters, numbers and _ allowed";
        $all_right = 1;
      }
    }
    if (!file_exists($_FILES['image']['tmp_name']) || !is_uploaded_file($_FILES['image']['tmp_name'])){
      $image_updated = false;
      
    } 
    else{
      $image_updated = true;
    }
    if($all_right != 1) {
      if($image_updated == true){
        $name = $_FILES['image']['name'];
        $name = '/images/'.$name;
        $curr_dir = getcwd();
        $dir = $curr_dir.'/images/';
        $target_file = $dir.basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $extensions_arr = array("jpg","jpeg","png","gif");
      }
      else{
        $name = $_SESSION['temp_image_src'];
      }
        if(in_array($imageFileType,$extensions_arr)||($image_updated == false)) {
            $image = basename( $_FILES["imageUpload"]["name"],".jpg");
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
              $temp_item_name = $_SESSION['temp_item_name'];
              $seller = $_SESSION["username"];
              $itemname = $_SESSION['to_update'];
              $sql = "DELETE FROM Items WHERE itemname = '$temp_item_name'";
              $conn->query($sql);
              $sql = "INSERT INTO Items (itemname, itemdesc,price,item_image,quantity,seller)
              VALUES ('$item_name','$description','$price','$name','$quantity','$seller')";
              if ($conn->query($sql) === TRUE) {
                if($image_updated == true){
                  move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
                }
                $image_uploaded = "image uploaded: ";
                $sql = "select item_image from Items where itemname = '$item_name'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
              }
              elseif($conn->error === "Duplicate entry '$item_name' for key 'Items.PRIMARY'"){
                  $item_nameErr = "Item name already taken";
              }
              else {
                echo $conn->error;
              }
            }
            $_SESSION['image_src'] = $name;
            $conn->close();

          } else {
            $imageErr = "File is not an image.";
            $uploadOk = 0;
          }
    }
  }
  if(($_SESSION['update']==true)&&($all_right == 0)){
        $submit_button = "Update";
        $image = basename( $_FILES["imageUpload"]["name"],".jpg");
        $servername = $_SESSION['servername'];
        $susername = $_SESSION['server_username'];
        $spassword = $_SESSION['password'];
        $dbname = $_SESSION['dbname'];
        // Create connection
        $conn = new mysqli($servername, $susername, $spassword, $dbname);
        $itemname = $_SESSION['to_update'];
        $sql = "SELECT * FROM Items WHERE itemname = "."'$itemname'";
        $result = $conn->query($sql);
        echo $conn->error;
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $_SESSION['temp_item_name'] = $row['itemname'];
                $item_name = $row['itemname'];
                $temp_description = $row['itemdesc'];
                $description = $row['itemdesc'];
                $temp_prices=$row['price'];
                $price = $row['price'];
                $temp_quantity=$row['quantity'];
                $quantity = $row['quantity'];
                $_SESSION['temp_image_src']=$row['item_image'];
                $_SESSION['image_src'] = $row['item_image'];
            }
        }
    
    }

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
?>

<h2>Update Item</h2>
      <p><span class="error">* required field</span></p>
      <form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
        <label for = "item_name">Item Name:</label><span class="error">* <?php echo $item_nameErr;?></span>
        <br><br>
        <input type="text" id="item_name" name="item_name" value="<?php echo $item_name;?>">
        <br><br>
        <label for = "description">Description:</label><span class="error">* <?php echo $descriptionErr;?></span>
        <br><br>
        <input type="text" id = "description" name="description" value="<?php echo $description;?>" placeholder = "200 charachters">
        <br><br>
        <label for = "price">Price(INR):</label><span class="error">* <?php echo $priceErr;?></span>
        <br><br>
        <input type="text" id = "price" name="price" value="<?php echo $price;?>">
        <br><br>
        <label for = "quantity">Quantity:</label><span class="error">* <?php echo $quantityErr;?></span>
        <br><br>
        <input type="number" id="quantity" name="quantity" min="1" max="99" value="<?php echo $quantity;?>" placeholder = "1 - 99">
        <br><br>
        <label for = "image">Select image to upload:</label><span class="error">* <?php echo $imageErr;?></span>
        <br><br>
        <input type="file" name="image" id="image"><p>image uploaded:</p><img src = <?php echo $_SESSION['image_src'];?>>
        <br><br>
        <input type="submit" value=<?php echo $submit_button?> name="submit">
      </form>
      <h3><a href="dashboard_seller.php">Dashboard</a></h3>
</body>
</html>