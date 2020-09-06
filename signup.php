<!DOCTYPE HTML>  
<html>
<head>
<link rel="stylesheet" href="/signup.css"> 
<style>
.error {color: #FF0000;}
</style>
</head>
  <body>  

  <?php
  session_start();
  // define variables and set to empty values
  $firstnameErr = $lastnameErr = $emailErr = $user_typeErr = $usernameErr = $passwordErr = "";
  $firstname = $lastname = $email = $user_type = $username = $password = "";
  $all_right = 0;
  $login_now = "";
  $_SESSION['logged_in'] = false;
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["firstname"])) {
      $firstnameErr = "Firstname is required";
      $all_right = 1;
    } else {
      $firstname = test_input($_POST["firstname"]);
      // check if name only contains letters and whitespace
      if (!preg_match("/^[a-zA-Z]*$/",$firstname)) {
        $firstnameErr = "Only letters allowed";
        $all_right = 1;
      }
    }
    if (empty($_POST["lastname"])) {
      $lastnameErr = "Lastname is required";
      $all_right = 1;
    } else {
      $lastname = test_input($_POST["lastname"]);
      // check if name only contains letters and whitespace
      if (!preg_match("/^[a-zA-Z]*$/",$lastname)) {
        $lastnameErr = "Only letters allowed";
        $all_right = 1;
      }
    }
    if (empty($_POST["email"])) {
      $emailErr = "Email is required";
      $all_right = 1;
    } else {
      $email = test_input($_POST["email"]);
      // check if e-mail address is well-formed
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
        $all_right = 1;
      }
    }  
    if (empty($_POST["username"])) {
      $usernameErr = "Username is required";
      $all_right = 1;
    } else {
      $username = test_input($_POST["username"]);
      // check if username only contains letters _
      if (!preg_match("/^[\w]{4,24}$/",$username)) {
        $usernameErr = "only letters, numbers and _ allowed";
        $all_right = 1;
      }
    }
    if (empty($_POST["password"])) {
      $passwordErr = "Password is required";
      $all_right = 1;
    } else {
      $password = $_POST["password"];
      $password = trim($password);
      $password = htmlspecialchars($password);
      // check if password only contains letters _
      if (!preg_match("/^[\w*.!@#$%^&(){}[\]:;<>,.?\/~+\-=\\\\|]{8,24}$/",$password)) {
        $passwordErr = "password should be between 8 and 24 charachters";
        $all_right = 1;
      }
    }
    if (empty($_POST["user_type"])) {
      $user_typeErr = "User type is required";
      $all_right = 1;
    } else {
      $user_type = test_input($_POST["user_type"]);
    }

    if($all_right == 0){
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
        $sql = "INSERT INTO User (username, password, firstname, lastname, email, user_type)
        VALUES ('$username','$password','$firstname','$lastname','$email','$user_type')";
        if ($conn->query($sql) === TRUE) {
          $login_now = "<a href="."login.php".">Login Now</a>"; 
        } else {
          if($conn->error === "Duplicate entry '$username' for key 'User.PRIMARY'"){
              $usernameErr = "username already taken";
          }
        }
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
    <div id = "container">
      <h2>Sign-up</h2>
      <p><span class="error">* required field</span></p>
      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
        <label for = "firstname">Firstname:</label><span class="error">* <?php echo $firstnameErr;?></span>
        <br><br>
        <input type="text" id="firstname" name="firstname" value="<?php echo $firstname;?>">
        <br><br>
        <label for = "lastname">Lastname:</label><span class="error">* <?php echo $lastnameErr;?></span>
        <br><br>
        <input type="text" id = "lastname" name="lastname" value="<?php echo $lastname;?>">
        <br><br>
        <label for = "email">E-mail Id:</label><span class="error">* <?php echo $emailErr;?></span>
        <br><br>
        <input type="text" id = "email" name="email" value="<?php echo $email;?>">
        <br><br>
        <label for = "username">Username:</label><span class="error">* <?php echo $usernameErr;?></span>
        <br><br>
        <input type="text" id="username" name="username" value="<?php echo $username;?>" placeholder = "4 to 24 characters">
        <br><br>
        <label for = "password">Password:</label><span class="error">* <?php echo $passwordErr;?></span>
        <br><br>
        <input type="text" id="password" name="password" value="<?php echo $password;?>" placeholder = "8 to 24 characters">
        <br><br>
        <label for = "user_type">User Type:</label><br><br>
        <input type="radio" id = "buyer" name="user_type" <?php if (isset($user_type) && $user_type=="buyer") echo "checked";?> value="buyer"><label  for = "buyer">buyer</label> 
        <input type="radio" id = "seller" name="user_type" <?php if (isset($user_type) && $user_type=="seller") echo "checked";?> value="seller"><label for = "seller">seller</label> 
        <span class="error">*<?php echo $user_typeErr;?></span>
        <br><br>
        <input type="submit" name="submit" value="Create Account">
        <br><br>
        <p><?php echo $login_now;?></p>
      </form>
    </div>
  </body>
</html>