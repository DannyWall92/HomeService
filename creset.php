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
        if (isset($_GET['action'])) {
            $action = mysqli_real_escape_string($link, htmlspecialchars($_GET['action']));
            if ($action == "send"){
                $email = mysqli_real_escape_string($link, htmlspecialchars($_GET['email']));
                $getEmQuery = "select ID from contractors where email like '$email'";
                $getEmResult = mysqli_query($link, $getEmQuery);
                if (mysqli_num_rows($getEmResult) == 1) {
                    // send reset email link with ID labeled item
                    $hash = mysqli_real_escape_string($link, htmlspecialchars($_GET['hash']));
                    $subject = 'Password Reset Link';
                    $message = 'To reset your password click on the link below' . "\r\n" . "http://dewdevelopment.com/HomeService/creset.php?action=reset&hash=chi937djkieydyns736djleuidnmw83490dhjk36&email=$email";
                    $headers = 'From: Danny <danny@dewdevelopment.com>' . "\r\n" . 'Reply-To: danny@dewdevelopment.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
                    mail($email, $subject, $message, $headers);
                    $send = "yes";
                } else {
                    $send = "crash";
                }
            }
            if ($action == "reset") {
                $email = mysqli_real_escape_string($link, htmlspecialchars($_GET['email']));
                $hash = mysqli_real_escape_string($link, htmlspecialchars($_GET['hash']));
                if ($hash = "chi937djkieydyns736djleuidnmw83490dhjk36") {
                    $resetQuery = "Select * from contractors where email like '$email'";
                    $resetResult = mysqli_query($link, $resetQuery);
                    if (mysqli_num_rows($resetResult) == 1) {
                        $Row = mysqli_fetch_assoc($resetResult);
                        $email = $Row['email'];
                        $send = "edit";
                    } else {
                        $send = "crash";
                    }
                }
            }
        }
        if (isset($_POST['doit'])) {
            $doit = mysqli_real_escape_string($link, htmlspecialchars($_POST['doit']));
            if ($doit == "modify") {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $email = mysqli_real_escape_string($link, htmlspecialchars($_POST['email']));
                $updQuery = "update contractors set password = '$password' where email like '$email'";
                $updResult = mysqli_query($link, $updQuery);
                $send = "complete";
            } else {
                $send = "crash";
            }
        }
        

        // header("Location: index.php");
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
                <h1 style="text-align:center;">Reset your password</h1>
            </div>
        </header>
        
        <div id="container" class="container">

        <?php
        if (isset($_Get['action']) != true && isset($send) != true) {
        ?>
            <h2>Enter the email address you registered with to receive a reset link</h2>
            <form action="creset.php" method="get">
                <input type="hidden" name="action" value="send">
                Email: <input type="text" name="email"><br />
                <input type="submit" name="submit" value="Submit">
            </form>
        <?php
        }
        ?>

        <?php
        if ($send == "yes") {
        ?>
            <h2>Reset link has been sent to your email</h2>
        <?php
        }
        if ($send == "crash") {
        ?>
            <h2>Fatal error: contact support</h2>
        <?php
        }
        if ($send == "edit") {
        ?>
            <h2>Reset your password</h2>
            <form action="creset.php" method="post">
                <input type="hidden" name="doit" value="modify">
                <input type="hidden" name="email" value='<?php echo ("$email") ?>'>
                Email: <?php echo ("$email") ?> <br />
                password: <input type='text' name='password'><br />
                <input type="submit" name="submit" value="Update">
            </form>

        <?php
        }
        if ($send == "complete"){
        ?>
        <h2>Password Now Reset</h2>
        Return to <a href="login.php">Login</a>
        <?php
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