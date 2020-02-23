<?php

class RouteOrganizer
{


    public static function isUserLoggedIn(): bool
    {
        if ( isset($_SESSION['strava']) && strtotime($_SESSION['strava']['expiration']) < time() ) {
            return true;
        }
        return false;
    }


    public static function auth(): void
    {
        include __DIR__.'/../views/auth.php';
    }


    public static function directory(): void
    {
        
    }


}