<?php
    session_start();
    include __DIR__."/autoload.php";

    $organizer = new RouteOrganizer;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "components/meta.php" ?>
    <title>RouteOrganizer for your Strava routes</title>
</head>
<body>
    
    <?php include "components/header.php" ?>

    <main class="main">
        <div class="output">
            <?php

                // When User is logged in
                if ($organizer->isUserloggedIn()) {
                    if (isset($_SESSION['folders'])) include __DIR__."/templates/listing.php";
                    else {
                        $error = $organizer->error_message;
                        include __DIR__."/templates/error.php";
                    }
                }
                // Login caused error
                else if (isset($_GET["error"])) {
                    $error = "The authentication failed. Strava is reporting: <i>{$_GET['error']}</i>";
                    include __DIR__."/templates/error.php";
                }
                // Login suceed, exchange tokens
                else if (isset($_GET["code"], $_GET["scope"])) {
                    if (!$organizer->tokenExchange($_GET["code"], $_GET["scope"])) {
                        $error = $organizer->error_message;
                        include __DIR__."/templates/error.php";
                    }
                }
                // Let the user authenticate
                else {
                    include __DIR__."/templates/auth.php";
                }

            ?>
        </div>
    </main>

    <?php include "components/footer.php" ?>

</body>
</html>