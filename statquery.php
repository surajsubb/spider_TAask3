<!DOCTYPE HTML>  
<html>
<head>
<link rel="stylesheet" href="show_item.css"> 
<link rel="stylesheet" href="stat.css"> 
<script type="text/javascript" src='stat.js'> </script>
</head>
<?php
session_start();
$username = $_SESSION['username'];
$servername = $_SESSION['servername'];
$susername = $_SESSION['server_username'];
$spassword = $_SESSION['password'];
$dbname = $_SESSION['dbname'];

// Create connection
$conn = new mysqli($servername, $susername, $spassword, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
else {

  /* Declare an array containing our data points */
   $data_points = array();

  /* Usual SQL Queries */
     $sql = "SELECT itemname, SUM(order_size) AS quantity_sold FROM Orders WHERE seller_id = '$username' GROUP BY itemname";
     $result = $conn->query($sql);
     if(mysqli_num_rows($result) == 0){
        echo "No items Bought Yet";
      }
    while($row = $result->fetch_assoc())
    {       
        $itemname = $row['itemname'];
        $quantity = $row['quantity_sold'];
        $sql = "SELECT price FROM Items WHERE itemname = '$itemname'";
        $Result = $conn->query($sql);
        while($row = $Result->fetch_assoc())
        {
            $price = $row['price'];       
            $point = array("itemname" =>  $itemname ,"quantity" =>  $quantity, "price" => $price);
            array_push($data_points, $point);
        }
    }

    /* Encode this array in JSON form */
    }
    $conn->close();
?>
<p id="hide1"><?php echo json_encode($data_points, JSON_NUMERIC_CHECK);?></p>
<h2> Statistics </h2>
<div id = "graphs">
  <canvas id="canvas_bar"></canvas>
  <legend for="canvas_bar"></legend>

  <canvas id="canvas_pie"></canvas>
  <legend for="canvas_pie"></legend>
</div>


<script type="text/javascript" src="stat.js"></script>
<h3><a href="dashboard_seller.php">Back to Dashboard</a></h3>

</html>