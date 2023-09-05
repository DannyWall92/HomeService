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
        $c = mysqli_real_escape_string($link, htmlspecialchars($_POST['c']));
        if (!is_numeric($c)) {
            $c = 1;
        }
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = mysqli_real_escape_string($link, htmlspecialchars($_POST['email']));
        $query1 = "insert into users (password, email, c) VALUES ('$password', '$email', $c)";
        $result = mysqli_query($link, $query1);
        $query2 = "select ID from users where email like '$email' and password like '$password'";
        $result = mysqli_query($link, $query2);
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['ID'];
        // setcookie("userid", $userID, time() + (86400 * 30));
        // setcookie("email", $email, time() + (86400 * 30));
        $expiry = time() + (86400*30);
        $data = (object) array( "user_id" => $user_id, "email" => $email );
        $cookieData = (object) array( "data" => $data, "expiry" => $expiry );
        setcookie( "user_data", json_encode( $cookieData ), $expiry );
        header("Location: index.php");    


        // create default home maintenance schedule for this user
        $defQuery = "select * from home_maintenance where userID = 1";
        $defResult = mysqli_query($link, $defQuery);
        while ($defRow = mysqli_fetch_assoc($defResult)) {
            $name = $defRow['name'];
            $frequency = $defRow['frequency'];
            $frequency_detail = $defRow['frequency_detail'];
            $insQuery = "insert into home_maintenance (name, frequency, frequency_detail, userID) values ('$name', '$frequency', '$frequency_detail', $userID)";
            $insResult = mysqli_query($link, $insQuery);
        }
        header("Location: index.php");
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