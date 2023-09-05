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
            if(isset($_GET["c"])) {
                $c = $_GET["c"];
                header("Location: login.php?c=$c");
            }
        } else {
            $user_data = json_decode($_COOKIE["hs_user_data"]);
            $user_data_time = $user_data->expiry;
            if ($user_data_time > time()) {
                include 'config.php';
                $link = mysqli_connect($host, $dbuser, $dbpass, $dbname);
                $user_id = $user_data->data->user_id;
                $email = $user_data->data->email;
                $query = "select c.name, c.logo, c.hvacphone, c.elecphone, c.plumbphone from users u inner join contractors c ON u.contractorID = c.ID where u.email like '$email'";
                $result = mysqli_query($link, $query);
                $row = mysqli_fetch_assoc($result);
                $cName = $row['name'];
                $cLogo = $row['logo'];
                $cHVACphone = $row['hvacphone'];
                $cElecphone = $row['elecphone'];
                $cPlumbphone = $row['plumbphone'];

                // $query = "select c.name, c.logo, c.hvacphone, c.elecphone, c.plumbphone from users u inner join contractors c ON u.contractorID = c.ID where u.email like '$email'";
                // $result = mysqli_query($link, $query);
                // $row = mysqli_fetch_assoc($result);
            } else {
                header("Location: login.php");
                if(isset($_GET["c"])) {
                    $c = $_GET["c"];
                    header("Location: login.php?c=$c");
                }
            }
        }
    ob_end_flush();
?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="styles.css"/>
        <link rel="manifest" href="manifest.json">
        <meta name="author" content="Danny Wall"/>

        <title>Home Maintenance App</title>
        <style>
            .container::after {
                content: "";
                background: url(<?php echo ($cLogo) ?>);
                background-repeat: no-repeat;
                background-position: 50% 30%;
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
                <h2 style="text-align:center;">By: <?php echo ($cName) ?></h2>
            </div>
        </header>
        
        <div id="container" class="container">
            <div class="menu">
                <p class="mainmenu"><a href="todo.php">Maintenance To Do List</a></p>
                <p class="mainmenu"><a href="schedule.php">Set Maintenance Schedule</a></p>
                <p class="mainmenu"><a href="history.php">See Full History Report</a></p>
                <p class="mainmenu"><a href="tel:<?php echo ($cHVACphone) ?>">Contact AC/Heating Repair</a></p>
                <p class="mainmenu"><a href="tel:<?php echo ($cPlumbphone) ?>">Contact Plumbing Repair</a></p>
                <p class="mainmenu"><a href="tel:<?php echo ($cElecphone) ?>">Contact Electrical Repair</a></p>
                <p class="mainmenu"><a href="logout.php">Logout</a></p>
            </div>
        </div><!-- /content -->
        
        <footer>
            <p style="text-align: center;">
            &copy; 2020 DEW Development. All rights Reserved. <br />For support contact <a href="mailto:support@dewdevelopment.com" style="color: yellow;">support</a> by email.
            </p>
        </footer>
    </body>
    <script>
        if ('serviceWorker' in navigator) {
            console.log("Will the service worker register?");
            navigator.serviceWorker.register('service-worker.js')
            .then(function(reg){
                console.log("Yes, it did.");
            }).catch(function(err) {
                console.log("No it didn't. This happened: ", err)
            });
        }
    </script>
</html>