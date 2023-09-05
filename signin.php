<?php
    ob_start();
        //If the HTTPS is not found to be "on"
        if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on") {
            //Tell the browser to redirect to the HTTPS URL.
            header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
            //Prevent the rest of the script from executing.
            exit;
        }
    
        include 'config.php';
        $link = mysqli_connect($host, $dbuser, $dbpass, $dbname);
        $email = $_POST['email'];
        $email = mysqli_real_escape_string($link, htmlspecialchars($email));
        $password = $_POST['password'];
        $query = "select ID, password from users where email like '$email'";
        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['ID'];
        $hash = $row['password'];
        if (password_verify($password, $hash)) {
            // setcookie("userid", $userid, time() + (86400 * 30), "/");
            // setcookie("email", $email, time() + (86400 * 30), "/");
            $expiry = time() + (86400*30);
            $data = (object) array( "user_id" => $user_id, "email" => $email );
            $cookieData = (object) array( "data" => $data, "expiry" => $expiry );
            setcookie( "hs_user_data", json_encode( $cookieData ), $expiry );
            header("Location: index.php");    
        } else {
            header("Location: login.php");    
        }
    ob_end_flush();
    //below this, comment out header redirect above for debug purposes
?>
<html>
    <body>
        <?php
            echo ($userid . "<BR /><br />" . $password . "<br />" . $hash . "<br /><br />");
            echo ($query . "<br /><br />");
            echo ($source . " " . $cio . " " . $howmany . " " . $title);
            echo ("<br /><br />");
            var_dump($_REQUEST);
            echo ("<br /><br />");
            var_dump($_POST);
        ?>
    </body>
</html>