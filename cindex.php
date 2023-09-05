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
    

        if(!isset($_COOKIE["cemail"])) {
            header("Location: clogin.php");
        } else {
            include 'config.php';
            $link = mysqli_connect($host, $dbuser, $dbpass, $dbname);
            $cemail = $_COOKIE["cemail"];

            if (isset($_POST['action'])) {
                $action = $_POST['action'];
                $action = mysqli_real_escape_string($link, htmlspecialchars($action));
                if ($action = "update") {
                    $logo = mysqli_real_escape_string($link, htmlspecialchars($_POST['logo']));
                    $cname = mysqli_real_escape_string($link, htmlspecialchars($_POST['name']));
                    $hvacphone = mysqli_real_escape_string($link, htmlspecialchars($_POST['hvacphone']));
                    $elecphone = mysqli_real_escape_string($link, htmlspecialchars($_POST['elecphone']));
                    $plumbphone = mysqli_real_escape_string($link, htmlspecialchars($_POST['plumbphone']));
                    $updQuery = "update contractors set logo='$logo',name='$cname',hvacphone='$hvacphone',elecphone='$elecphone', plumbphone='$plumbphone' where email like '$cemail'";
                    $updResult = mysqli_query($link, $updQuery);
                }
            }

            
            $query = "select * from contractors where email like '$cemail'";
            $result = mysqli_query($link, $query);
            $row = mysqli_fetch_assoc($result);
            $cID = $row['ID'];
            $cName = $row['name'];
            $cLogo = $row['logo'];
            $cHVACphone = $row['hvacphone'];
            $cElecphone = $row['elecphone'];
            $cPlumbphone = $row['plumbphone'];
        }
    ob_end_flush();
?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="styles.css"/>
        <meta name="author" content="Danny Wall"/>

        <title>Home Maintenance App</title>

        <style>
            .container::after {
                content: "";
                background: url(<?php echo ($cLogo) ?>);
                background-repeat: no-repeat;
                background-position: center;
                opacity: 0.1;
                top: 0;
                left: 0;
                bottom: 0;
                right: 0;
                position: absolute;
                z-index: -1;
                
            }
        </style>
    </head>
    <body>

        <header>
            <div class="headline">
                <h1 style="text-align:center;">Home Maintenance App</h1>
            </div>
        </header>
        
        <div id="container" class="container">
            <h2><?php echo ($cName) ?> Info Added To App</h2>
            <p>The below information will be added to the app for any homeowner you give your link to.</P>
            <p>Your link: http://dewdevelopment.com/HomeService/index.php?c=<?php echo ($cID) ?></p>
            <p>It might be a good idea to take the link above and go to bitly.com and shorten the link but ALSO create a custom link with the name of your contracting business</p>
            <form action="cindex.php" method="post">
                <input type="hidden" name="action" value="update">
                Your Name: <input type="text" name="name" value="<?php echo ($cName) ?>"><br />
                Link to Your Logo: <input type="text" name="logo" value="<?php echo ($cLogo) ?>"><br />
                HVAC Phone: <input type="text" name="hvacphone" value="<?php echo ($cHVACphone) ?>"><br />
                Electrical Phone: <input type="text" name="elecphone" value="<?php echo ($cElecphone) ?>"><br />
                Plumbing Phone: <input type="text" name="plumbphone" value="<?php echo ($cPlumbphone) ?>"><br />
                <input type="submit" name="submit" value="Submit Changes">
            </form>
            <h4>When completed <a href="clogout.php">click here to logout</a></h4>
            

        </div><!-- /content -->
        
        <footer>
            <p style="text-align: center;">
                &copy; 2020 DEW Development. All rights Reserved. <br />For support contact <a href="mailto:support@dewdevelopment.com" style="color: yellow;">support</a> by email.
            </p>
        </footer>
    </body>
</html>