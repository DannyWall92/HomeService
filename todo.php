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
    if (isset($_GET['action'])){
        $action = htmlspecialchars($_GET['action']);   
        $ID = mysqli_real_escape_string($link, htmlspecialchars($_GET['item']));
        if (is_numeric($ID) != true) {
            $action = "crash";
        }
        if ($action == "start") {
            $startedQuery = "update todo set done = 2 where ID = $ID and userID = $user_id";
            $startedResult = mysqli_query($link, $startedQuery);
        }
        if ($action == "done") {
            $curDayNum = ltrim(date('d'), "0");
            $curYearNum = date('Y');
            $curMonth = date('F');
            $notes = mysqli_real_escape_string($link, htmlspecialchars($_GET['notes']));
            $doneQuery = "update todo set done=3, notes='$notes', completed_month='$curMonth', completed_day=$curDayNum, completed_year=$curYearNum WHERE ID = $ID and userID = $user_id";
            $doneResult = mysqli_query($link, $doneQuery);
            $getNameSQL = "select name from todo where ID = $ID and userID = $user_id";
            $getNameResult = mysqli_query($link, $getNameSQL);
            $nameRow = mysqli_fetch_assoc($getNameResult);
            $name = $nameRow['name'];
            $histSQL = "insert into history (name, userID, completed_month, completed_day, completed_year, notes) VALUES ('$name', $user_id, '$curMonth', $curDayNum, $curYearNum, '$notes')";
            $histResult = mysqli_query($link, $histSQL);
        }
    }

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
                <h1 style="text-align:center;">Home Maintenance App</h1>
            </div>
        </header>
        
        <div id="container" class="container">
            <P><div class="nav"><a href="item.php?action=add">New Item</a></div> | <div class="nav"><a href="schedule.php">Full Schedule</a></div></P>

<?php

    // *******************************************************************
    // * FIRST ADD ITEMS TO TODO TABLE OR RESET "DONE" FIELD IF NEW YEAR *
    // *******************************************************************

    $curDayNum = ltrim(date('d'), "0");
    $curYearNum = date('Y');
    $curMonth = date('F');
    $schQuery = "select * from maintenance_schedule where userID = $user_id";
    $schResult = mysqli_query($link, $schQuery);
    $rows = mysqli_num_rows ($schResult);
    if ($rows > 0) {
        while ($schRow = mysqli_fetch_assoc($schResult)) {
            $ID = $schRow['ID'];
            $name = $schRow['name'];
            $frequency = $schRow['frequency'];
            $frequency_detail = $schRow['frequency_detail'];

            if ($frequency == "monthly" || strtolower($frequency) == strtolower($curMonth)) {
                if ($frequency_detail <= $curDayNum) {
                    $todoQuery = "select * from todo where ID = $ID";
                    $todoResult = mysqli_query($link, $todoQuery);
                    $numTodoRows = mysqli_num_rows ($todoResult);    
                    if ($numTodoRows == 0) {
                        $insQuery = "insert into todo (ID, name, frequency, frequency_detail, userID, month, year) VALUES ($ID, '$name', '$frequency', $frequency_detail, $user_id, '$curMonth', $curYearNum)";
                        $insResult = mysqli_query($link, $insQuery);
                    } else {
                        $insRow = mysqli_fetch_assoc($todoResult);
                        $name = $insRow['name'];
                        $frequency = $insRow['frequency'];
                        $frequency_detail = $insRow['frequency_detail'];
                        $done = $insRow['done'];
                        $month = $insRow['month'];
                        $year = $insRow['year'];
                        if ($frequency == "monthly" && strtolower($month) != strtolower($curMonth)){
                            $updQuery = "update todo set month = '$curMonth', year = $curYearNum, done = 1 where ID = $ID and $user_id = $user_id";
                            $updResult = mysqli_query($link, $updQuery);
                        }
                        if ($frequency != "monthly" && $year < $curYearNum) {
                            $updQuery = "update todo set month = '$curMonth', year = $curYearNum, done = 1 where ID = $ID and $user_id = $user_id";
                            $updResult = mysqli_query($link, $updQuery);
                        }
                    }
                }
            }
        }
    }


    // ********************************
    // * NOW DISPLAY IN PROCESS TABLE *
    // ********************************
    $getTodosQuery = "select * from todo where userID = $user_id and done = 2 order by done desc";
    $getTodosResult = mysqli_query($link, $getTodosQuery);
    $numRows = mysqli_num_rows($getTodosResult);
    if ($numRows > 0) {
        echo ("<h2>Items In Progress</h2>");
        echo ("<table border=1>");
            echo ("<tr>");
                echo ("<td><strong>Name</strong></td>");
                echo ("<td><strong>Action</strong></td>");
            echo ("</tr>");
            while ($Row = mysqli_fetch_assoc($getTodosResult)) {
                $ID = $Row['ID'];
                $name = $Row['name'];
                $done = $Row['done'];
                echo ("<tr>");
                    if ($done == 1) {
                        echo ("<td>$name</td>");
                    } else {
                        echo ("<td><strong>$name</strong></td>");
                    }
                    echo ("<td><form action='done.php' method='GET'><input type='hidden' name='action' value='finish' /><input type='hidden' name='item' value='$ID' /><input type='submit' name='submit' value='Completed' /></form></td>");
                    // echo ("<td><a href='todo.php?action=done&item=$ID'>Completed</a></td>");
                echo ("</tr>");
            }
        echo ("</table>");
    }


    // **************************
    // * NOW DISPLAY TODO TABLE *
    // **************************
    echo ("<h2>Items Not Started Yet</h2>");
    echo ("<table border=1>");
        echo ("<tr>");
            echo ("<td><strong>Name</strong></td>");
            echo ("<td><strong>Action</strong></td>");
        echo ("</tr>");
        $getTodosQuery = "select * from todo where userID = $user_id and done = 1 order by done desc";
        $getTodosResult = mysqli_query($link, $getTodosQuery);
        while ($Row = mysqli_fetch_assoc($getTodosResult)) {
            $ID = $Row['ID'];
            $name = $Row['name'];
            $done = $Row['done'];
            echo ("<tr>");
                if ($done == 1) {
                    echo ("<td>$name</td>");
                } else {
                    echo ("<td><strong>$name</strong></td>");
                }
                echo ("<td><form action='todo.php' method='GET'><input type='hidden' name='action' value='start' /><input type='hidden' name='item' value='$ID' /><input type='submit' name='submit' value='Start' /></form>");
                echo ("<form action='done.php' method='GET'><input type='hidden' name='action' value='finish' /><input type='hidden' name='item' value='$ID' /><input type='submit' name='submit' value='Completed' /></form></td>");
                // echo ("<button id=btn$ID>Complete</button></td>");
                // echo ("<td><a href='todo.php?action=start&item=$ID'>Start</a> | ");
                // echo ("<a href='todo.php?action=done&item=$ID'>Completed</a></td>");
            echo ("</tr>");
        }
    echo ("</table>");
?>










        </div><!-- /content -->
        
        <footer>
            <p style="text-align: center;">
            &copy; 2020 DEW Development. All rights Reserved. <br />For support contact <a href="mailto:support@dewdevelopment.com" style="color: yellow;">support</a> by email.
            </p>
        </footer>
    </body>
</html>