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
    
        if(!isset($_COOKIE["hs_user_data"])) {
            header("Location: login.php");
        } else {
            $user_data = json_decode($_COOKIE["hs_user_data"]);
            $user_data_time = $user_data->expiry;
            $user_id = $user_data->data->user_id;
            $email = $user_data->data->email;
        }
    ob_end_flush();
    // $user_id = $_COOKIE["userid"];
    include 'config.php';
    $link = mysqli_connect($host, $dbuser, $dbpass, $dbname);
?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="styles.css"/>
        <meta name="author" content="Danny Wall"/>

        <title>Home Maintenance App</title>

    </head>
    <body>

        <header>
            <div class="headline">
                <h1 style="text-align:center;">Full Maintenance History Report</h1>
            </div>
        </header>
        
        <div id="container" class="container">
            
        <?php

            $Query = "select * from history where userID = $user_id";
            $Result = mysqli_query($link, $Query);
            $rows = mysqli_num_rows ($Result);
            if ($rows > 0) {
                echo ("<style> td { padding: 5px; } </style>");
                echo ("<table border='0'>");
                echo ("<tr><td><strong>Maintenance Item</strong></td><td><strong>Completed</strong></td></tr>");
                while ($Row = mysqli_fetch_assoc($Result)) {
                    $name = $Row['name'];
                    $month = $Row['completed_month'];
                    $day = $Row['completed_day'];
                    $year = $Row['completed_year'];
                    $notes = $Row['notes'];
                    echo ("<tr>");
                    echo ("<td>$name</td>");
                    echo ("<td>$month $day, $year</td></tr>");
                    if (strlen($notes) > 1) {
                        echo ("<tr><td colspan=\"2\"><ul><li style=\"list-style-type: '*'\">$notes</ul></td></tr>");
                    } else {
                        echo ("<tr><td colspan=\"2\"></td></tr>");
                    }
                    
                }
                echo ("</table>");
            }
?>
        </div><!-- /content -->
        
        <footer>
            <p style="text-align: center;">
            &copy; 2020 DEW Development. All rights Reserved. <br />For support contact <a href="mailto:support@dewdevelopment.com" style="color: yellow;">support</a> by email.
            </p>
        </footer>
    </body>
</html>