<!DOCTYPE HTML>  
<html>
<head>
<link rel="stylesheet" href="/login.css"> 
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
$_SESSION['logged_in'] = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
        $all_right = 1;
    } 
    else {
        $username = test_input($_POST["username"]);
        // check if username only contains letters _
        if (!preg_match("/^[\w]{4,24}$/",$username)) {
            $usernameErr = "only letters, numbers and '_' allowed";
            $all_right = 1;
        }
     }
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
        $all_right = 1;
    } 
    else {
        $password = $_POST["password"];
        $password = trim($password);
        $password = htmlspecialchars($password);
        // check if password only contains letters
        if (!preg_match("/^[\w*.!@#$%^&(){}[\]:;<>,.?\/~+\-=\\\\|]*$/",$password)) {
            $passwordErr = "password contains unknown characters";
            $all_right = 1;
        }
    }
    if($all_right == 0){
        $servername = $_SESSION['servername'];
        $susername = $_SESSION['server_username'];
        $spassword = $_SESSION['password'];
        $dbname = $_SESSION['dbname'];

        // Create connection
        $conn = new mysqli($servername, $susername, $spassword, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT username, password, firstname, user_type FROM User WHERE username = '$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                if($row["password"] == $password){
                    $_SESSION['user_type'] = $row["user_type"];
                    $_SESSION['username'] = $username;
                    $type = $row["user_type"];
                    header("location: dashboard_$type.php");
                }
                else {
                    echo "wrong username or password";
                }
            }
        } 
        else {
            echo "wrong username or password";
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
    <h2 id = "header">Login</h2>
    <p><span class="error">* required field</span></p>
    <form id = "login_form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
        <label for = "username">Username:</label><span class="error">* <?php echo $usernameErr;?></span>
        <br><br>
        <input id="username" type="text" name="username" value="<?php echo $username;?>">
        <br><br>
        <label for = "password">Password:</label><span class="error">* <?php echo $passwordErr;?></span>
        <br><br>
        <input type="text" id="password" name="password" value="<?php echo $password;?>">
        <br><br>
        <input type="submit" name="submit" value="Login">  
    </form>
    <h3>Or Sign-up <a href="signup.php">here</a></h3>
</div>
</body>
</html>