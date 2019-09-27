<?php

    require __DIR__.'/../autoload.php';

    $routeOrganizer = new RouteOrganizer;
    $auth = new StravaAuth;

    $athlete = $routeOrganizer->athlete;
    $viewpath = __DIR__.'/../views';


    // When User is logged in
    if ($routeOrganizer->isUserloggedIn()) {
        
    }

    // Login error
    else if ( isset($_GET["error"]) ) {
        $error = "The authentication failed. Strava is reporting: <i>{$_GET['error']}</i>";
        $components =  ["$viewpath/error.php"];
    }

    // Other error
    else if ( isset($_SESSION['error']) ) {
        $error = $_SESSION['error'];
        $components =  ["$viewpath/error.php"];
    }

    // Login suceed, exchange tokens
    else if (isset($_GET["code"], $_GET["scope"])) {
        if ( $auth->tokenExchange($_GET['code'], $_GET['scope']) ) {
            if ( $routeOrganizer->getRoutes() ) {
                $routeOrganizer->orderRoutesToFolder();
                $components = ["$viewpath/listing.php"];
            }
            else {
                $error = $routeOrganizer->error;
                $components = ["$viewpath/error.php"];
            }
        }
        else {
            $error = $auth->error;
            $components = ["$viewpath/error.php"];
        }
    }

    // Nothing happened yet. Let the user authenticate
    else {
        $components =  ["$viewpath/auth.php"];
    };



    include __DIR__.'/../views/layout.php';

?>
