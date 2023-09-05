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
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = mysqli_real_escape_string($link, htmlspecialchars($_POST['email']));
        $query1 = "insert into contractors (password, email) VALUES ('$password', '$email')";
        $result = mysqli_query($link, $query1);
        $query2 = "select ID from contractors where email like '$email' and password like '$password'";
        $result = mysqli_query($link, $query2);
        $row = mysqli_fetch_assoc($result);
        $cID = $row['ID'];
        setcookie("cid", $cID, time() + (86400 * 30));
        setcookie("cemail", $email, time() + (86400 * 30));

        header("Location: cindex.php");
    ob_end_flush();
?>

<html>
    <body>
        <?php
            echo ($userID . "<BR /><br />" . $password . "<BR /><br />" . $email . "<BR /><br />");
            echo ($query1 . "<br /><br />" . $query2);
            echo ("<br /><br />");
            var_dump($_REQUEST);
            echo ("<br /><br />");
            var_dump($_POST);
        ?>
    </body>