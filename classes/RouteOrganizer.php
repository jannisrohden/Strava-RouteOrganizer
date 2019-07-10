<?php

    class RouteOrganizer
    {


        private $folders = [];





        /**
         * Initiates the routes
         */
        public function __construct() {
            $this->folders = [
                htmlspecialchars("route 1") => (object)['name' => 'Route 1', 'type' => 1, 'distance' => 70, "url" => "https://strava.com"],
                "folder_1" => [
                    "route2" => (object)['name' => 'Route 2', 'type' => 2, 'distance' => 120, "url" => "https://strava.com"],
                    "folder2" => [
                        "folder_3" => [],
                        "folder_4" => []
                    ]
                ]
            ];
        }





        /**
         * Searching all routes of an URI
         * @param string $uri The URI of the request
         * @return bool|object false if the URI is invalid or {items=>[], $level=>[], $breadcrumbs=>[]}
         */
        public function getRoutes($uri) {
            // Split the URI into an array
            $uriParts = explode('/', substr($uri, 1));
            $level = $this->folders;

            if ($uriParts[0] == '') unset($uriParts[0]);

            foreach ($uriParts as $part) {
                if (isset($level[$part])) {
                    $level = $level[$part];
                }
                else {
                    return false;
                }
            }
            return (object)[
                "items" => array_keys($level),
                "level" => $level,
                "breadcrumbs" => $uriParts
            ];
        }





        /**
         * Outputs an error page
         * @return string HTML code with the error message
         */
        public static function output404() {
            echo '<h1>Error 404 - This page does not exists!</h1>';
        }


    }