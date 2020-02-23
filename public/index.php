<?php

    session_start();

    require __DIR__.'/../vendor/autoload.php';
    require __DIR__.'/../src/RouteOrganizer.php';
    require __DIR__ . '/../src/Strava.php';

    $dotenv = new Symfony\Component\Dotenv\Dotenv;
    $dotenv->load(__DIR__.'/../.env');

    $routeOrganizer = new RouteOrganizer;
    $strava = new Strava($_ENV['STRAVA_CLIENT_ID'], $_ENV['STRAVA_CLIENT_SECRET']);

    if ( Strava::isUserLoggedIn() ) {
        $routeOrganizer->showDirectory();
    }
    elseif ( isset($_GET['code'], $_GET['scope'], $_GET['state']) ) {
        $strava->authenticate($_GET['code'], $_GET['scope'], $_GET['state']);
    }
    elseif ( isset($_GET['error']) ) {
        $routeOrganizer->showError($_GET['error']);
    }
    else {
        $routeOrganizer->showAuthDialog();
    }