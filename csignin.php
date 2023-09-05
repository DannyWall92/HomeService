<?php
    ob_start();
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
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
        $query = "select ID, password from contractors where email like '$email'";
        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_assoc($result);
        $cid = $row['ID'];
        $hash = $row['password'];
        if (password_verify($password, $hash)) {
            setcookie("cid", $userid, time() + (86400 * 30), "/");
            setcookie("cemail", $email, time() + (86400 * 30), "/");
            header("Location: cindex.php");    
        } else {
            header("Location: clogin.php");    
        }
    ob_end_flush();
    //comment out header redirect above for debug purposes
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