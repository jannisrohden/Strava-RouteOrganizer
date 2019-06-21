<?php

    class RouteOrganizer
    {


        private static $folders = [
            'foo' => [
                'route1',
                'test' => [
                    'bar' => ['Route2', 'Route3']
                ]
            ]
        ];


        /**
         * Searching all routes of an URI
         * @param string $uri The URI of the request
         * @return bool|array false if the URI is invalid or an array with the routes
         */
        public static function getRoutes($uri) {
            // Split the URI into an array
            $uriParts = explode('/', substr($uri, 1));
            // The actual level od the folders
            $level = self::$folders;
            
            // Search trough the parts of the URI
            foreach ($uriParts as $part) {
                // When this part is not represented by a folder
                if (!isset($level[$part])) {
                    return false;
                }
                // Go one level deeper
                $level = $level[$part];
            }

            return $level;
        }





        /**
         * Outputs an error page
         * @return string HTML code with the error message
         */
        public static function output404() {
            echo '<h1>Error 404 - Not found</h1>';
        }


    }