<?php
    ob_start();
        //If the HTTPS is not found to be "on"
        if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on") {
            //Tell the browser to redirect to the HTTPS URL.
            header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
            //Prevent the rest of the script from executing.
            exit;
        }
    
        if(isset($_COOKIE["hs_user_data"])) {
            header("Location: index.php");
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
                if(isset($_GET["c"])) {
                    $c = $_GET["c"];
                    header("Location: login.php?c=$c");
                }
            }
        }
        if(isset($_GET["c"])) {
            $c = $_GET["c"];
        } else {
            $c = "1";
        }
        include 'config.php';
        $link = mysqli_connect($host, $dbuser, $dbpass, $dbname);
        $query = "select name from contractors where ID = $c";
        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_assoc($result);
        $name = $row['name'];
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
                <h1 style="text-align: center;">Home Maintenance App</h1>
                <h2 style="text-align: center;">Brought to you by: <?php echo ($name) ?></h2>
            </div>
        </header>
        
        <section id="container" class="container">
            <p>By creating an account and using this app/site you agree to our terms of service, privacy policy, and cookie policy</p>
            <fieldset class="reader-config-group">
                <h4>Create Your Account</h4>
                <form action="create.php" method="post">
                    <input type="hidden" name="c" id="c" value="<?php echo ($c) ?>">
                    <table border='0'>
                        <tr><td>Email:</td><td><input type="text" id="email" name="email"></td></tr>
                        <tr><td>Password:</td><td><input type="text" id="password" name="password"></td></tr>
                        <tr><td colspan='2'><input type="submit" id="submit" name="submit" value="Submit"></td></tr>
                    </table>
                </form>
            </fieldset>
        </section><!-- /content -->
        
        <footer>
            <p style="text-align: center;">
            &copy; 2020 DEW Development. All rights Reserved. <br />For support contact <a href="mailto:support@dewdevelopment.com" style="color: yellow;">support</a> by email.
            </p>
        </footer>
    </body>
</html>