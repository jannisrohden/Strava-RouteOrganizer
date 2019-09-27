<?php
/**
 * All functionalities for processing and output the routes
 * @author Jannis Rohden <kontakt@jannisrohden.de>
 */

 

class RouteOrganizer implements RouteOrganizer_interface
{

    /** @var object|false All user information or false */
    public $athlete = false;

    /** @var string|null The last error message */
    public $error = null;

    /** @var object|null The folder tree with routes */
    public $folders = null;




    /**
     * Init
     */
    public function __construct() 
    {
        if ($this->isUserLoggedIn()) {
            $this->athlete = $this->getUserData();
        }
    }


    /**
     * Checks if the user is logged in
     * @return bool
     */
    public static function isUserLoggedIn() 
    {
        if ( isset($_SESSION['expiration']) AND $_SESSION['expiration'] > time() ) {
            return true;
        }
        return false;
    }




    /**
     * Returns all user information combined in an object
     * @return object All user information
     */
    public function getUserData() 
    {
        $out = $_SESSION['athlete'];
        $out->expiration = $_SESSION['expiration'];
        return $out;
    }




    /**
     * Fetch user's routes from strava
     * @return bool Success
     */
    public function getRoutes()
    {

    }




    /**
     * Process the routes and convert they names into folders
     * Stores the folders in session
     * @return bool Success
     */
    public function orderRoutesToFolders()
    {

    }


}