<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="/add_item_seller.css"> 
<style>
.error {color: #FF0000;}
</style>
</head>
<body>
<?php
  session_start();
  // define variables and set to empty values
  $item_nameErr = $descriptionErr = $priceErr = $quantityErr = $imageErr = "";
  $item_name = $description = $price = $quantity = $image = "";
  $all_right = 0;
  $login_now = "";
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
    if (!file_exists($_FILES['image']['tmp_name']) || !is_uploaded_file($_FILES['image']['tmp_name'])) {
      $imageErr = "image is required";
      $all_right = 1;
    } 
    elseif($all_right != 1) {
        $name = $_FILES['image']['name'];
        $curr_dir = getcwd();
        $dir = $curr_dir.'/images/';
        $target_file = $dir.basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $extensions_arr = array("jpg","jpeg","png","gif");
        // Check if image file is a actual image or fake image
        if(in_array($imageFileType,$extensions_arr)) {
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
              $seller = $_SESSION["username"];
              $sql = "INSERT INTO Items (itemname, itemdesc,price,item_image,quantity,seller)
              VALUES ('$item_name','$description','$price','".'/images/'.$name."','$quantity','$seller')";
              if ($conn->query($sql) === TRUE) {
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
                $sql = "select item_image from Items where itemname = '$item_name'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $image_src2 = $row['item_image'];
              }
              elseif($conn->error === "Duplicate entry '$item_name' for key 'Items.PRIMARY'"){
                  $item_nameErr = "Item name already taken";
              }
              else {
                echo $conn->error;
              }
            }

        } else {
          $imageErr = "File is not an image.";
          $uploadOk = 0;
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

<h2>Add Item</h2>
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
        <input type="file" name="image" id="image">
        <br><br>
        <input type="submit" value="Add Item" name="submit">
        <p><?php echo $login_now;?></p>
      </form>
      <img src = <?php echo $image_src2;?>>
</body>
</html>