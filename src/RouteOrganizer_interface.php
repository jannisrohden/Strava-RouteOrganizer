<?php

interface RouteOrganizer_Interface
{

    public static function isUserLoggedIn();
    public function getUserData();
    public function getRoutes();
    public function orderRoutesToFolders();

}