<?php

    session_start();

    require __DIR__.'/../vendor/autoload.php';
    require __DIR__.'/../src/RouteOrganizer.php';

    $dotenv = new Symfony\Component\Dotenv\Dotenv;
    $dotenv->load(__DIR__.'/../.env');

    if ( RouteOrganizer::isUserLoggedIn() ) {
        RouteOrganizer::directory();
    }
    else {
        RouteOrganizer::auth();
    }