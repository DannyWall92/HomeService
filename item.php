<?php
    ob_start();
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
    
    include 'config.php';
    $link = mysqli_connect($host, $dbuser, $dbpass, $dbname);
    if (ISSET($_POST['method'])){
        $method = mysqli_real_escape_string($link, htmlspecialchars($_POST['method']));
        if ($method == "insert") {
            $thename = mysqli_real_escape_string($link, htmlspecialchars($_POST['thename']));
            $frequency =  mysqli_real_escape_string($link, htmlspecialchars($_POST['frequency']));
            $frequency_detail =  mysqli_real_escape_string($link, htmlspecialchars($_POST['frequency_detail']));
            $insQuery = "insert into maintenance_schedule (name, frequency, frequency_detail, userID) VALUES ('$thename', '$frequency', '$frequency_detail', $user_id)";
            $insResult = mysqli_query($link, $insQuery);
            header("Location: schedule.php");
        }

        if ($method == "modify"){
            $ID = mysqli_real_escape_string($link, htmlspecialchars($_POST['id']));
            if (is_numeric($ID) == true) {
                $thename = mysqli_real_escape_string($link, htmlspecialchars($_POST['thename']));
                $frequency = mysqli_real_escape_string($link, htmlspecialchars($_POST['frequency']));
                $frequency_detail = mysqli_real_escape_string($link, htmlspecialchars($_POST['frequency_detail']));
                $modifyQuery = "update maintenance_schedule set name = '$thename' where ID = $ID and userID = $user_id";
                $modifyResult = mysqli_query($link, $modifyQuery);
                $modifyQuery = "update maintenance_schedule set frequency = '$frequency' where ID = $ID and userID = $user_id";
                $modifyResult = mysqli_query($link, $modifyQuery);
                $modifyQuery = "update maintenance_schedule set frequency_detail = '$frequency_detail' where ID = $ID and userID = $user_id";
                $modifyResult = mysqli_query($link, $modifyQuery);
                header("Location: schedule.php");
            }
        }
        if ($method == "del") {
            $ID = mysqli_real_escape_string($link, htmlspecialchars($_POST['id']));
            if (is_numeric($ID)) {
                $delQuery = "delete from maintenance_schedule where ID = $ID and userID = $user_id";
                $delResult = mysqli_query($link, $delQuery);
                header ("Location: schedule.php");
            }
        }
    }
    ob_end_flush();
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
            <p><a class="nav" href="item.php?action=add">New Item</a> <a class="nav" href="todo.php">Todo List</a> <a class="nav" href="schedule.php">Full Schedule</a></p>

            <?php
                if ($_GET['action'] == "add") {
                    echo ("<h2>Add Maintenance Item</h2>");
                    echo ("<form action='item.php' method='post'>");
                        echo ("<table border='0'>");
                        echo ("<input type='hidden' name='method' value='insert' />");
                        echo ("<tr><td>Name of maintenance:</td><td><input type='text' name='thename' /></td></tr>");
                        echo ("<tr><td>Frequency of maintenance:</td>");
                        echo ("<td><select name='frequency'>");
                            echo ("<option value='Monthly'>Monthly</option>");
                            echo ("<option value='January'>January</option>");
                            echo ("<option value='February'>February</option>");
                            echo ("<option value='March'>March</option>");
                            echo ("<option value='April'>April</option>");
                            echo ("<option value='May'>May</option>");
                            echo ("<option value='June'>June</option>");
                            echo ("<option value='July'>July</option>");
                            echo ("<option value='August'>August</option>");
                            echo ("<option value='September'>September</option>");
                            echo ("<option value='October'>October</option>");
                            echo ("<option value='November'>November</option>");
                            echo ("<option value='December'>December</option>");
                        echo ("</select></td></tr>");
                        echo ("<tr><td>When in the frequency:</td><td><input type='text' name='frequency_detail' /></td></tr>");
                        echo ("<tr><td colspan='2'><input type='submit' name='submit' value='Add Item' /></td></tr>");
                        echo ("</table>");
                    echo ("</form>");
                    echo ("For frequency: Input monthly for maintenance to occur every month, &quot;When in the frequency&quot; is the day you want it done, or add a month name for maintenance that is performed yearly; &quot;When in the frequency&quot; is the day of that month");
                }

                if ($_GET['action'] == "edit") {
                    $ID = mysqli_real_escape_string($link, htmlspecialchars($_GET['item']));
                    $editQuery = "select * from maintenance_schedule where ID = $ID and userID = $user_id";
                    $editResult = mysqli_query($link, $editQuery);
                    $rows = mysqli_num_rows ($editResult);
                    if ($rows == 1) {
                        $editRow = mysqli_fetch_assoc($editResult);
                        $thename = $editRow['name'];
                        $frequency = $editRow['frequency'];
                        $frequency_detail = $editRow['frequency_detail'];
                        echo ("<h2>Edit Maintenance Item</h2>");
                        echo ("<form action='item.php' method='post'>");
                            echo ("<table border='0'>");
                            echo ("<input type='hidden' name='method' value='modify' />");
                            echo ("<input type='hidden' name='id' value='$ID' />");
                            echo ("<tr><td>Name of maintenance:</td><td><input type='text' name='thename' value='$thename' /></td></tr>");
                            echo ("<tr><td>Frequency of maintenance:</td>");
                            echo ("<td><select name='frequency'>");
                                if ($frequency == "Monthly") {
                                    echo ("<option value='Monthly' selected>Monthly</option>");
                                } else {
                                    echo ("<option value='Monthly'>Monthly</option>");
                                }
                                if ($frequency == "January") {
                                    echo ("<option value='January' selected>January</option>");
                                } else {
                                    echo ("<option value='Monthly'>January</option>");
                                }
                                if ($frequency == "February") {
                                    echo ("<option value='February' selected>February</option>");
                                } else {
                                    echo ("<option value='February'>February</option>");
                                }
                                if ($frequency == "March") {
                                    echo ("<option value='March' selected>March</option>");
                                } else {
                                    echo ("<option value='March'>March</option>");
                                }
                                if ($frequency == "April") {
                                    echo ("<option value='April' selected>April</option>");
                                } else {
                                    echo ("<option value='April'>April</option>");
                                }
                                if ($frequency == "May") {
                                    echo ("<option value='May' selected>May</option>");
                                } else {
                                    echo ("<option value='May'>May</option>");
                                }
                                if ($frequency == "June") {
                                    echo ("<option value='June' selected>June</option>");
                                } else {
                                    echo ("<option value='June'>June</option>");
                                }
                                if ($frequency == "July") {
                                    echo ("<option value='July' selected>July</option>");
                                } else {
                                    echo ("<option value='July'>July</option>");
                                }
                                if ($frequency == "August") {
                                    echo ("<option value='August' selected>August</option>");
                                } else {
                                    echo ("<option value='August'>August</option>");
                                }
                                if ($frequency == "September") {
                                    echo ("<option value='September' selected>September</option>");
                                } else {
                                    echo ("<option value='September'>September</option>");
                                }
                                if ($frequency == "October") {
                                    echo ("<option value='October' selected>October</option>");
                                } else {
                                    echo ("<option value='October'>October</option>");
                                }
                                if ($frequency == "November") {
                                    echo ("<option value='November' selected>November</option>");
                                } else {
                                    echo ("<option value='November'>November</option>");
                                }
                                if ($frequency == "December") {
                                    echo ("<option value='December' selected>December</option>");
                                } else {
                                    echo ("<option value='December'>December</option>");
                                }
                            echo ("</select></td></tr>");
                            echo ("<tr><td>When in the frequency:</td><td><input type='text' name='frequency_detail' value='$frequency_detail' /></td></tr>");
                            echo ("<tr><td colspan='2'><input type='submit' name='submit' value='Edit Item' /></td></tr>");
                            echo ("</table>");
                        echo ("</form>");
                        echo ("For frequency: Input monthly for maintenance to occur every month, &quot;When in the frequency&quot; is the day you want it done, or add a month name for maintenance that is performed yearly; &quot;When in the frequency&quot; is the day of that month");
                    } else {
                        echo ("<h2>CRITICAL ERROR: ITEM DOES NOT SEEM TO BELONG TO YOU! EDITING THIS ITEM NOT POSSIBLE</h2>");
                    }
                }

                if ($_GET['action'] == "delete") {
                    $ID = mysqli_real_escape_string($link, htmlspecialchars($_GET['item']));
                    $editQuery = "select * from maintenance_schedule where ID = $ID and userID = $user_id";
                    $editResult = mysqli_query($link, $editQuery);
                    $rows = mysqli_num_rows ($editResult);
                    if ($rows == 1) {
                        $editRow = mysqli_fetch_assoc($editResult);
                        $thename = $editRow['name'];
                        $frequency = $editRow['frequency'];
                        $frequency_detail = $editRow['frequency_detail'];
                        echo ("<h2>Are You Sure You Want To Delete This Item???</h2>");
                        echo ("<form action='item.php' method='post'>");
                            echo ("<table border='0'>");
                            echo ("<input type='hidden' name='method' value='del' />");
                            echo ("<input type='hidden' name='id' value='$ID' />");
                            echo ("<tr><td>Name of maintenance:</td><td><input type='text' name='thename' value='$thename' /></td></tr>");
                            echo ("<tr><td>Frequency of maintenance:</td><td><input type='text' name='frequency' value='$frequency' /></td></tr>");
                            echo ("<tr><td>When in the frequency:</td><td><input type='text' name='frequency_detail' value='$frequency_detail' /></td></tr>");
                            echo ("<tr><td colspan='2'><input type='submit' name='submit' value='YES' /></td></tr>");
                            echo ("</table>");
                            echo ("<br />Tapping 'YES' above deletes the item and can not be undone.  Hit the back button on your browser to cancel this operation");
                        echo ("</form>");
                    } else {
                        echo ("<h2>CRITICAL ERROR: ITEM DOES NOT SEEM TO BELONG TO YOU! DELETING THIS ITEM NOT POSSIBLE</h2>");
                    }
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