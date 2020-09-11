<?php
session_start();
?>
<!DOCTYPE HTML>  
<html>
    <?php
    $_SESSION['searchErr'] = "";
        $username = $_SESSION['username'];
        $type = $_SESSION['user_type'];
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            if (empty($_POST["search_text"])) {
                $_SESSION['searchErr'] = "Search Field Empty!";
              } else {
                $search_text = test_input($_POST["search_text"]);
                $_SESSION['sql'] = "SELECT * FROM Items WHERE itemname LIKE '%$search_text%' OR itemdesc LIKE '%$search_text%'";
              }
              $_SESSION['from_search'] = true;
              header("location: dashboard_buyer.php");
        }

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        //OR itemdesc ~* '$search_text'
    ?>
</html>