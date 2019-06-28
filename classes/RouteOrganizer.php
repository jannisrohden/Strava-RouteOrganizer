<?php

    class RouteOrganizer
    {


        private $folders = [];





        /**
         * Connects to Strava
         */
        public function __construct() {
            $this->folders = [
                'route1' => (object)["name" => "route1", "url" => "https://strava.com"],
                'route2' => (object)["name" => "route2", "url" => "https://strava.com"],
                'foo' => [
                    'route3' => (object)["name" => "route3", "url" => "https://strava.com"],
                    'test' => [
                        'bar' => ['Route4' => (object)["name" => "route4", "url" => "https://strava.com"]]
                    ]
                ]
            ];
        }





        /**
         * Searching all routes of an URI
         * @param string $uri The URI of the request
         * @return bool|array false if the URI is invalid or an array with the routes
         */
        public function getRoutes($uri) {
            // Split the URI into an array
            $uriParts = explode('/', substr($uri, 1));
            $level = $this->folders;

            if ($uriParts[0] == '') unset($uriParts[0]);

            foreach ($uriParts as $part) {
                // When this part is not represented by a folder
                if (!isset($level[$part])) {
                    return false;
                }
                // Go one level deeper
                $level = $level[$part];
            }

            return array_keys($level);
        }





        /**
         * Outputs an error page
         * @return string HTML code with the error message
         */
        public static function output404() {
            echo '<h1>Error 404 - Not found</h1>';
        }


    }