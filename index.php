<?php
include 'create_databases.php';
?>
<!DOCTYPE HTML>  
<html>
    <head>
        <link rel="stylesheet" href="/index.css"> 
    </head>
    <div id="container">
        <h1>WELCOME TO MYSTORE</h1>
        <form action = "login.php">
            <input type = "submit" value="login">
        </form>
        <form action = "signup.php">
            <input type = "submit" value="sign up">
        </form>
    </div>
    
</html>
<?php
/*TODO
#MAKE SURE PEOPLE CANT ACCESS WITHOUT LOGGING INOR ACCESS OTHERS ONCE LOGGED IN
#UPLOAD NEW STUFF
?>