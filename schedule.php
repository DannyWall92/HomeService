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
            if ($user_data_time > time()) {
                include 'config.php';
                $link = mysqli_connect($host, $dbuser, $dbpass, $dbname);
                $user_id = $user_data->data->user_id;
                $email = $user_data->data->email;
            } else {
                header("Location: login.php");
            }
        }
    
    ob_end_flush();
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
                <div style="float: left;"><a href="index.php"><img src="https://img.icons8.com/office/30/000000/home.png"/></a></div>
                <h1 style="text-align:center;">Maintenance Schedule</h1>
            </div>
        </header>
        
        <div id="container" class="container">
            <p><div class="nav"><a href="item.php?action=add">New Item</a></div> | <div class="nav"><a href="todo.php">Todo List</a></div></p>
            <p>Our maintenance schedule came from <a href="https://www.bhg.com/home-improvement/advice/home-maintenance-checklist/">this site here</a>.  I simply took the seasonal items and placed them into months within the season to make it a little easier to get things done because in any given month the list isn't too crazy.</p>
            <p>Understand that for where you live, your weather conditions, or the specific things at your home you may need to delete or modify items in this list.</p>
            <p>As an example, the full preloaded list includes an item for a septic tank, another for a tankless hotwater heater, which many homes do not have so you may want to delete this item or other items related to equipment (dishwasher, AC traditional hotwater heater, tankless hotwater heater, septic tank, etc.) if you do not have them.</p>

        <?php

            $mnthQuery = "select * from maintenance_schedule where frequency like 'monthly' and userID = $user_id";
            $mnthResult = mysqli_query($link, $mnthQuery);
            $rows = mysqli_num_rows ($mnthResult);
            if ($rows > 0) {
                echo ("<h2>Monthly Maintenance</h2>");
                echo ("<table border='1'>");
                echo ("<tr>");
                echo ("<td><strong>Name</strong></td>");
                echo ("<td><strong>Frequency</strong></td>");
                echo ("<td><strong>When</strong></td>");
                echo ("<td><strong>Action</strong></td>");
                echo ("</tr>");
                while ($mnthRow = mysqli_fetch_assoc($mnthResult)) {
                    $ID = $mnthRow['ID'];
                    $name = $mnthRow['name'];
                    $frequency = $mnthRow['frequency'];
                    $frequency_detail = $mnthRow['frequency_detail'];
                    echo ("<tr>");
                    echo ("<td>$name</td>");
                    echo ("<td class='tdcenter'>$frequency</td class=>");
                    echo ("<td class='tdcenter'>$frequency_detail</td class=>");
                    echo ("<td>");
                    // echo ("<a href='item.php?action=edit&item=$ID'>Edit</a>|<a href='item.php?action=delete&item=$ID'>Delete</a>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='edit'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Edit'></form>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='delete'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Delete'></form>");
                    echo ("</td>");
                    echo ("</tr>");
                }
                echo ("</table>");
            }
            
            $mnthQuery = "select * from maintenance_schedule where frequency like 'January' and userID = $user_id";
            $mnthResult = mysqli_query($link, $mnthQuery);
            $rows = mysqli_num_rows ($mnthResult);
            if ($rows > 0) {
                echo ("<h2>January Maintenance</h2>");
                echo ("<table border='1'>");
                echo ("<tr>");
                echo ("<td><strong>Name</strong></td>");
                echo ("<td><strong>Frequency</strong></td>");
                echo ("<td><strong>When</strong></td>");
                echo ("<td><strong>Action</strong></td>");
                echo ("</tr>");
                while ($mnthRow = mysqli_fetch_assoc($mnthResult)) {
                    $ID = $mnthRow['ID'];
                    $name = $mnthRow['name'];
                    $frequency = $mnthRow['frequency'];
                    $frequency_detail = $mnthRow['frequency_detail'];
                    echo ("<tr>");
                    echo ("<td>$name</td>");
                    echo ("<td class='tdcenter'>$frequency</td class=>");
                    echo ("<td class='tdcenter'>$frequency_detail</td>");
                    echo ("<td>");
                    // echo ("<a href='item.php?action=edit&item=$ID'>Edit</a>|<a href='item.php?action=delete&item=$ID'>Delete</a>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='edit'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Edit'></form>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='delete'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Delete'></form>");
                    echo ("</td>");
                    echo ("</tr>");
                }
                echo ("</table>");
            }

            $mnthQuery = "select * from maintenance_schedule where frequency like 'February' and userID = $user_id";
            $mnthResult = mysqli_query($link, $mnthQuery);
            $rows = mysqli_num_rows ($mnthResult);
            if ($rows > 0) {
                echo ("<h2>February Maintenance</h2>");
                echo ("<table border='1'>");
                echo ("<tr>");
                echo ("<td><strong>Name</strong></td>");
                echo ("<td><strong>Frequency</strong></td>");
                echo ("<td><strong>When</strong></td>");
                echo ("<td><strong>Action</strong></td>");
                echo ("</tr>");
                while ($mnthRow = mysqli_fetch_assoc($mnthResult)) {
                    $ID = $mnthRow['ID'];
                    $name = $mnthRow['name'];
                    $frequency = $mnthRow['frequency'];
                    $frequency_detail = $mnthRow['frequency_detail'];
                    echo ("<tr>");
                    echo ("<td>$name</td>");
                    echo ("<td class='tdcenter'>$frequency</td class=>");
                    echo ("<td class='tdcenter'>$frequency_detail</td>");
                    echo ("<td>");
                    // echo ("<a href='item.php?action=edit&item=$ID'>Edit</a>|<a href='item.php?action=delete&item=$ID'>Delete</a>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='edit'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Edit'></form>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='delete'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Delete'></form>");
                    echo ("</td>");
                    echo ("</tr>");
                }
                echo ("</table>");
            }

            $mnthQuery = "select * from maintenance_schedule where frequency like 'March' and userID = $user_id";
            $mnthResult = mysqli_query($link, $mnthQuery);
            $rows = mysqli_num_rows ($mnthResult);
            if ($rows > 0) {
                echo ("<h2>March Maintenance</h2>");
                echo ("<table border='1'>");
                echo ("<tr>");
                echo ("<td><strong>Name</strong></td>");
                echo ("<td><strong>Frequency</strong></td>");
                echo ("<td><strong>When</strong></td>");
                echo ("<td><strong>Action</strong></td>");
                echo ("</tr>");
                while ($mnthRow = mysqli_fetch_assoc($mnthResult)) {
                    $ID = $mnthRow['ID'];
                    $name = $mnthRow['name'];
                    $frequency = $mnthRow['frequency'];
                    $frequency_detail = $mnthRow['frequency_detail'];
                    echo ("<tr>");
                    echo ("<td>$name</td>");
                    echo ("<td class='tdcenter'>$frequency</td class=>");
                    echo ("<td class='tdcenter'>$frequency_detail</td>");
                    echo ("<td>");
                    // echo ("<a href='item.php?action=edit&item=$ID'>Edit</a>|<a href='item.php?action=delete&item=$ID'>Delete</a>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='edit'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Edit'></form>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='delete'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Delete'></form>");
                    echo ("</td>");
                    echo ("</tr>");
                }
                echo ("</table>");
            }

            $mnthQuery = "select * from maintenance_schedule where frequency like 'April' and userID = $user_id";
            $mnthResult = mysqli_query($link, $mnthQuery);
            $rows = mysqli_num_rows ($mnthResult);
            if ($rows > 0) {
                echo ("<h2>April Maintenance</h2>");
                echo ("<table border='1'>");
                echo ("<tr>");
                echo ("<td><strong>Name</strong></td>");
                echo ("<td><strong>Frequency</strong></td>");
                echo ("<td><strong>When</strong></td>");
                echo ("<td><strong>Action</strong></td>");
                echo ("</tr>");
                while ($mnthRow = mysqli_fetch_assoc($mnthResult)) {
                    $ID = $mnthRow['ID'];
                    $name = $mnthRow['name'];
                    $frequency = $mnthRow['frequency'];
                    $frequency_detail = $mnthRow['frequency_detail'];
                    echo ("<tr>");
                    echo ("<td>$name</td>");
                    echo ("<td class='tdcenter'>$frequency</td class=>");
                    echo ("<td class='tdcenter'>$frequency_detail</td>");
                    echo ("<td>");
                    // echo ("<a href='item.php?action=edit&item=$ID'>Edit</a>|<a href='item.php?action=delete&item=$ID'>Delete</a>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='edit'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Edit'></form>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='delete'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Delete'></form>");
                    echo ("</td>");
                    echo ("</tr>");
                }
                echo ("</table>");
            }

            $mnthQuery = "select * from maintenance_schedule where frequency like 'May' and userID = $user_id";
            $mnthResult = mysqli_query($link, $mnthQuery);
            $rows = mysqli_num_rows ($mnthResult);
            if ($rows > 0) {
                echo ("<h2>May Maintenance</h2>");
                echo ("<table border='1'>");
                echo ("<tr>");
                echo ("<td><strong>Name</strong></td>");
                echo ("<td><strong>Frequency</strong></td>");
                echo ("<td><strong>When</strong></td>");
                echo ("<td><strong>Action</strong></td>");
                echo ("</tr>");
                while ($mnthRow = mysqli_fetch_assoc($mnthResult)) {
                    $ID = $mnthRow['ID'];
                    $name = $mnthRow['name'];
                    $frequency = $mnthRow['frequency'];
                    $frequency_detail = $mnthRow['frequency_detail'];
                    echo ("<tr>");
                    echo ("<td>$name</td>");
                    echo ("<td class='tdcenter'>$frequency</td class=>");
                    echo ("<td class='tdcenter'>$frequency_detail</td>");
                    echo ("<td>");
                    // echo ("<a href='item.php?action=edit&item=$ID'>Edit</a>|<a href='item.php?action=delete&item=$ID'>Delete</a>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='edit'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Edit'></form>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='delete'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Delete'></form>");
                    echo ("</td>");
                    echo ("</tr>");
                }
                echo ("</table>");
            }

            $mnthQuery = "select * from maintenance_schedule where frequency like 'June' and userID = $user_id";
            $mnthResult = mysqli_query($link, $mnthQuery);
            $rows = mysqli_num_rows ($mnthResult);
            if ($rows > 0) {
                echo ("<h2>June Maintenance</h2>");
                echo ("<table border='1'>");
                echo ("<tr>");
                echo ("<td><strong>Name</strong></td>");
                echo ("<td><strong>Frequency</strong></td>");
                echo ("<td><strong>When</strong></td>");
                echo ("<td><strong>Action</strong></td>");
                echo ("</tr>");
                while ($mnthRow = mysqli_fetch_assoc($mnthResult)) {
                    $ID = $mnthRow['ID'];
                    $name = $mnthRow['name'];
                    $frequency = $mnthRow['frequency'];
                    $frequency_detail = $mnthRow['frequency_detail'];
                    echo ("<tr>");
                    echo ("<td>$name</td>");
                    echo ("<td class='tdcenter'>$frequency</td class=>");
                    echo ("<td class='tdcenter'>$frequency_detail</td>");
                    echo ("<td>");
                    // echo ("<a href='item.php?action=edit&item=$ID'>Edit</a>|<a href='item.php?action=delete&item=$ID'>Delete</a>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='edit'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Edit'></form>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='delete'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Delete'></form>");
                    echo ("</td>");
                    echo ("</tr>");
                }
                echo ("</table>");
            }

            $mnthQuery = "select * from maintenance_schedule where frequency like 'July' and userID = $user_id";
            $mnthResult = mysqli_query($link, $mnthQuery);
            $rows = mysqli_num_rows ($mnthResult);
            if ($rows > 0) {
                echo ("<h2>July Maintenance</h2>");
                echo ("<table border='1'>");
                echo ("<tr>");
                echo ("<td><strong>Name</strong></td>");
                echo ("<td><strong>Frequency</strong></td>");
                echo ("<td><strong>When</strong></td>");
                echo ("<td><strong>Action</strong></td>");
                echo ("</tr>");
                while ($mnthRow = mysqli_fetch_assoc($mnthResult)) {
                    $ID = $mnthRow['ID'];
                    $name = $mnthRow['name'];
                    $frequency = $mnthRow['frequency'];
                    $frequency_detail = $mnthRow['frequency_detail'];
                    echo ("<tr>");
                    echo ("<td>$name</td>");
                    echo ("<td class='tdcenter'>$frequency</td class=>");
                    echo ("<td class='tdcenter'>$frequency_detail</td>");
                    echo ("<td>");
                    // echo ("<a href='item.php?action=edit&item=$ID'>Edit</a>|<a href='item.php?action=delete&item=$ID'>Delete</a>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='edit'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Edit'></form>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='delete'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Delete'></form>");
                    echo ("</td>");
                    echo ("</tr>");
                }
                echo ("</table>");
            }

            $mnthQuery = "select * from maintenance_schedule where frequency like 'August' and userID = $user_id";
            $mnthResult = mysqli_query($link, $mnthQuery);
            $rows = mysqli_num_rows ($mnthResult);
            if ($rows > 0) {
                echo ("<h2>August Maintenance</h2>");
                echo ("<table border='1'>");
                echo ("<tr>");
                echo ("<td><strong>Name</strong></td>");
                echo ("<td><strong>Frequency</strong></td>");
                echo ("<td><strong>When</strong></td>");
                echo ("<td><strong>Action</strong></td>");
                echo ("</tr>");
                while ($mnthRow = mysqli_fetch_assoc($mnthResult)) {
                    $ID = $mnthRow['ID'];
                    $name = $mnthRow['name'];
                    $frequency = $mnthRow['frequency'];
                    $frequency_detail = $mnthRow['frequency_detail'];
                    echo ("<tr>");
                    echo ("<td>$name</td>");
                    echo ("<td class='tdcenter'>$frequency</td class=>");
                    echo ("<td class='tdcenter'>$frequency_detail</td>");
                    echo ("<td>");
                    // echo ("<a href='item.php?action=edit&item=$ID'>Edit</a>|<a href='item.php?action=delete&item=$ID'>Delete</a>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='edit'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Edit'></form>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='delete'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Delete'></form>");
                    echo ("</td>");
                    echo ("</tr>");
                }
                echo ("</table>");
            }

            $mnthQuery = "select * from maintenance_schedule where frequency like 'September' and userID = $user_id";
            $mnthResult = mysqli_query($link, $mnthQuery);
            $rows = mysqli_num_rows ($mnthResult);
            if ($rows > 0) {
                echo ("<h2>September Maintenance</h2>");
                echo ("<table border='1'>");
                echo ("<tr>");
                echo ("<td><strong>Name</strong></td>");
                echo ("<td><strong>Frequency</strong></td>");
                echo ("<td><strong>When</strong></td>");
                echo ("<td><strong>Action</strong></td>");
                echo ("</tr>");
                while ($mnthRow = mysqli_fetch_assoc($mnthResult)) {
                    $ID = $mnthRow['ID'];
                    $name = $mnthRow['name'];
                    $frequency = $mnthRow['frequency'];
                    $frequency_detail = $mnthRow['frequency_detail'];
                    echo ("<tr>");
                    echo ("<td>$name</td>");
                    echo ("<td class='tdcenter'>$frequency</td class=>");
                    echo ("<td class='tdcenter'>$frequency_detail</td>");
                    echo ("<td>");
                    // echo ("<a href='item.php?action=edit&item=$ID'>Edit</a>|<a href='item.php?action=delete&item=$ID'>Delete</a>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='edit'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Edit'></form>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='delete'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Delete'></form>");
                    echo ("</td>");
                    echo ("</tr>");
                }
                echo ("</table>");
            }

            $mnthQuery = "select * from maintenance_schedule where frequency like 'October' and userID = $user_id";
            $mnthResult = mysqli_query($link, $mnthQuery);
            $rows = mysqli_num_rows ($mnthResult);
            if ($rows > 0) {
                echo ("<h2>October Maintenance</h2>");
                echo ("<table border='1'>");
                echo ("<tr>");
                echo ("<td><strong>Name</strong></td>");
                echo ("<td><strong>Frequency</strong></td>");
                echo ("<td><strong>When</strong></td>");
                echo ("<td><strong>Action</strong></td>");
                echo ("</tr>");
                while ($mnthRow = mysqli_fetch_assoc($mnthResult)) {
                    $ID = $mnthRow['ID'];
                    $name = $mnthRow['name'];
                    $frequency = $mnthRow['frequency'];
                    $frequency_detail = $mnthRow['frequency_detail'];
                    echo ("<tr>");
                    echo ("<td>$name</td>");
                    echo ("<td class='tdcenter'>$frequency</td class=>");
                    echo ("<td class='tdcenter'>$frequency_detail</td>");
                    echo ("<td>");
                    // echo ("<a href='item.php?action=edit&item=$ID'>Edit</a>|<a href='item.php?action=delete&item=$ID'>Delete</a>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='edit'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Edit'></form>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='delete'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Delete'></form>");
                    echo ("</td>");
                    echo ("</tr>");
                }
                echo ("</table>");
            }

            $mnthQuery = "select * from maintenance_schedule where frequency like 'November' and userID = $user_id";
            $mnthResult = mysqli_query($link, $mnthQuery);
            $rows = mysqli_num_rows ($mnthResult);
            if ($rows > 0) {
                echo ("<h2>November Maintenance</h2>");
                echo ("<table border='1'>");
                echo ("<tr>");
                echo ("<td><strong>Name</strong></td>");
                echo ("<td><strong>Frequency</strong></td>");
                echo ("<td><strong>When</strong></td>");
                echo ("<td><strong>Action</strong></td>");
                echo ("</tr>");
                while ($mnthRow = mysqli_fetch_assoc($mnthResult)) {
                    $ID = $mnthRow['ID'];
                    $name = $mnthRow['name'];
                    $frequency = $mnthRow['frequency'];
                    $frequency_detail = $mnthRow['frequency_detail'];
                    echo ("<tr>");
                    echo ("<td>$name</td>");
                    echo ("<td class='tdcenter'>$frequency</td class=>");
                    echo ("<td class='tdcenter'>$frequency_detail</td>");
                    echo ("<td>");
                    // echo ("<a href='item.php?action=edit&item=$ID'>Edit</a>|<a href='item.php?action=delete&item=$ID'>Delete</a>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='edit'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Edit'></form>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='delete'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Delete'></form>");
                    echo ("</td>");
                    echo ("</tr>");
                }
                echo ("</table>");
            }

            $mnthQuery = "select * from maintenance_schedule where frequency like 'December' and userID = $user_id";
            $mnthResult = mysqli_query($link, $mnthQuery);
            $rows = mysqli_num_rows ($mnthResult);
            if ($rows > 0) {
                echo ("<h2>December Maintenance</h2>");
                echo ("<table border='1'>");
                echo ("<tr>");
                echo ("<td><strong>Name</strong></td>");
                echo ("<td><strong>Frequency</strong></td>");
                echo ("<td><strong>When</strong></td>");
                echo ("<td><strong>Action</strong></td>");
                echo ("</tr>");
                while ($mnthRow = mysqli_fetch_assoc($mnthResult)) {
                    $ID = $mnthRow['ID'];
                    $name = $mnthRow['name'];
                    $frequency = $mnthRow['frequency'];
                    $frequency_detail = $mnthRow['frequency_detail'];
                    echo ("<tr>");
                    echo ("<td>$name</td>");
                    echo ("<td class='tdcenter'>$frequency</td class=>");
                    echo ("<td class='tdcenter'>$frequency_detail</td>");
                    echo ("<td>");
                    // echo ("<a href='item.php?action=edit&item=$ID'>Edit</a>|<a href='item.php?action=delete&item=$ID'>Delete</a>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='edit'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Edit'></form>");
                    echo ("<form action='item.php' method='GET'><input type='hidden' name='action' value='delete'> <input type='hidden' name='item' value='$ID'><input type='submit' name='submit' value='Delete'></form>");
                    echo ("</td>");
                    echo ("</tr>");
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