<?php
    ob_start();
    //If the HTTPS is not found to be "on"
    if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on") {
        //Tell the browser to redirect to the HTTPS URL.
        header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
        //Prevent the rest of the script from executing.
        exit;
    }
    $expiry = time() -7000000 ;
    $user_data = json_decode($_COOKIE["hs_user_data"]);
    $user_id = $user_data->data->user_id;
    $email = $user_data->data->email;
    $data = (object) array( "user_id" => $user_id, "email" => $email );
    $cookieData = (object) array( "data" => $data, "expiry" => $expiry );
    setcookie("user_data", json_encode( $cookieData ), $expiry);
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
                <h1 style="text-align:center;">You have Been Logged Out</h1>
            </div>
        </header>
        
        <div id="container" class="container">
            <h2>You should restart your browser to ensure you are fully logged out</h2>
        </div><!-- /content -->
        
        <footer>
            <p style="text-align: center;">
            &copy; 2020 DEW Development. All rights Reserved. <br />For support contact <a href="mailto:support@dewdevelopment.com" style="color: yellow;">support</a> by email.
            </p>
        </footer>
    </body>
</html>