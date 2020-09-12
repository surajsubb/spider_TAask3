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
$date="";
$item_null="";
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
else {

   $data_points = array();
    if (($_SERVER["REQUEST_METHOD"] == "POST")){
      if(isset($_POST['time_is'])){
          if(!empty($_POST['time'])){
              if($_POST['time'] == "day"){
                  $date = "AND Order_date >= ADDDATE(CURDATE(),INTERVAL -1 DAY)";
              }
              elseif($_POST['time'] == "week"){
                $date = "AND Order_date >= ADDDATE(CURDATE(),INTERVAL -7 DAY)";
              }
              elseif($_POST['time'] == "month"){
                $date = "AND Order_date >= ADDDATE(CURDATE(),INTERVAL -30 DAY)";
              }
              else{
                $date = "AND Order_date >= ADDDATE(CURDATE(),INTERVAL -365 DAY)";
              }
              $_SESSION['sql'] = $_SESSION['sql']." ORDER BY $order_by";
          }
      }
    }

     $sql = "SELECT itemname, SUM(order_size) AS quantity_sold FROM Orders WHERE seller_id = '$username' ".$date." GROUP BY itemname";
     $result = $conn->query($sql);
     if(mysqli_num_rows($result) == 0){
        $item_null = "No items Bought Yet";
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

    }
    $conn->close();
?>
<p id="hide1"><?php echo json_encode($data_points, JSON_NUMERIC_CHECK);?></p>
<h2> Statistics </h2>
<h3><a id="back" href="dashboard_seller.php">Back to Dashboard</a></h3>
<div id = "graphs">
  <div id="time_options">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <select name="time" id="time">
          <option value="day">day</option>
          <option value="week">week</option>
          <option value="month">month</option>
          <option value="year">year</option>
      </select>
      <input id="time_is" type="submit" name = "time_is" value="Go">
    </form>
  </div>
  <?php
  if($item_null != "No items Bought Yet"){
  ?>
    <canvas id="canvas_bar"></canvas>
    <legend for="canvas_bar"></legend>

    <canvas id="canvas_pie"></canvas>
    <legend for="canvas_pie"></legend>
  <?php
  }
  else{
  ?>
    <h3><?php echo $item_null; ?></h3>
  <?php
  }
  ?>
</div>


<script type="text/javascript" src="stat.js"></script>

</html>