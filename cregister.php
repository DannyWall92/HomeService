<?php
    ob_start();
        // if(isset($_COOKIE["cemail"])) {
            // header("Location: cindex.php");
        // }
            //If the HTTPS is not found to be "on"
    if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on") {
        //Tell the browser to redirect to the HTTPS URL.
        header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
        //Prevent the rest of the script from executing.
        exit;
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
                <h1 style="text-align: center;">Home Maintenance App</h1>
            </div>
        </header>
        
        <section id="container" class="container">
            <p>By creating an account and using this app/site you agree to our terms of service, privacy policy, and cookie policy</p>
            <fieldset class="reader-config-group">
                <h4>Create Your Contractor Login</h4>
                <form action="ccreate.php" method="post">
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
                Home contractors <a href="clogin.php" style="color: yellow;">login here</a> or create your contractor account <a href="cregister.php" style="color: yellow;">here</a>
            </p>
        </footer>
    </body>
</html>