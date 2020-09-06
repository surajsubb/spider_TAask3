<?php
session_start();
$_SESSION['servername'] = "localhost";
$_SESSION['server_username'] = "spider";
$_SESSION['password'] = "random";

$servername = $_SESSION['servername'];
$username = $_SESSION['server_username'];
$password = $_SESSION['password'];

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
// Create database
$sql = "CREATE DATABASE IF NOT EXISTS Store";
$conn->query($sql);
$_SESSION['dbname'] = "Store";
$dbname = $_SESSION['dbname'];
$conn = new mysqli($servername, $username, $password, $dbname);
//table for items
$sql = "CREATE TABLE IF NOT EXISTS Items (
    itemname VARCHAR(24) PRIMARY KEY,
    itemdesc VARCHAR(200) NOT NULL,
    price VARCHAR(20) NOT NULL,
    item_image longtext NOT NULL,
    quantity INT(10) NOT NULL,
    seller varchar(20) NOT NULL,
    added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
  $curr_dir = getcwd();
  $dir = $curr_dir.'/images';
  if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
  }
if ($conn->query($sql) === TRUE) {
    //echo "successss";
} else {
  echo "Error creating TABLE: " . $conn->error;
}
//table for users
$sql = "CREATE TABLE IF NOT EXISTS User (
  username VARCHAR(24) PRIMARY KEY,
  password VARCHAR(24) NOT NULL,
  firstname VARCHAR(30) NOT NULL,
  lastname VARCHAR(30) NOT NULL,
  email VARCHAR(50) NOT NULL,
  user_type VARCHAR(8) NOT NULL,
  reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  )";
if ($conn->query($sql) === TRUE) {
  //echo "successss";
} else {
echo "Error creating TABLE: " . $conn->error;
}
//table to track what is bought and sold
$sql = "CREATE TABLE IF NOT EXISTS Orders (
  orderno INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  order_size INT NOT NULL,
  customer_id VARCHAR(30) NOT NULL,
  seller_id VARCHAR(30) NOT NULL,
  itemname VARCHAR(24) NOT NULL,
  order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  )";
if ($conn->query($sql) === TRUE) {
  //echo "successss";
} else {
echo "Error creating TABLE: " . $conn->error;
}
//table to track customer carts
$sql = "CREATE TABLE IF NOT EXISTS Cart (
  cartno INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  customer_id VARCHAR(30) NOT NULL,
  quantity INT(10) NOT NULL,
  seller_id VARCHAR(30) NOT NULL,
  itemname VARCHAR(24) NOT NULL,
  order_date TIMESTAMP 
  )";
if ($conn->query($sql) === TRUE) {
  //echo "successss";
} else {
echo "Error creating TABLE: " . $conn->error;
}
$conn->close();

?>