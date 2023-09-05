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
    include 'config.php';
    $link = mysqli_connect($host, $dbuser, $dbpass, $dbname);
    if (isset($_GET['action'])){
        $action = htmlspecialchars($_GET['action']);   
        $ID = mysqli_real_escape_string($link, htmlspecialchars($_GET['item']));
        if (is_numeric($ID) != true) {
            $action = "crash";
        }
        if ($action == "finish") {
            $nameSQL = "select name from todo where ID = $ID";
            $nameResult = mysqli_query($link, $nameSQL);
            $nameRow = mysqli_fetch_assoc($nameResult);
            $name = $nameRow['name'];
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
                <h1 style="text-align:center;">Home Maintenance App</h1>
            </div>
        </header>
        
        <div id="container" class="container">
<?php
if ($action == "finish"){
?>
            <form action="todo.php" method="GET">
                <input type="hidden" name="action" value="done">
                <input type="hidden" name="item" value="<?php echo $ID ?>">
                <label for="notes">Any notes regarding the completion of <span style="text-decoration: underline; font-style: italic;"><?php echo $name ?></span> or reminders for next time?</label><br />
                <textarea name="notes" rows="4" cols="70"></textarea><br />
                <input type="submit" name="submit" value="Finish"><br /><br />
                Tip: Always leave notes.  This will make your full history report more valuable and allow for greater resale value on your house as you can print the full history and provide it to a prospective home buyer as proof the home was regularly and well maintained.
            </form>
            <p>
                <a href="todo.php">Oops ... take me back, I am not done yet</a>
            </p>
<?php
} else {
            echo ("<h2>Fatal Error</h2>");
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