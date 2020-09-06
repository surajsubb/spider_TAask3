<?php
session_start();
if(!isset($_SESSION['user_type']) || $_SESSION['user_type']!="buyer"){
    header("location: login.php");
}
?>
<!DOCTYPE HTML>  
<html>
<h1>Welcome <?php echo $_SESSION['username'];?> you are a <?php echo $_SESSION['user_type'];?></h1> 
</html>