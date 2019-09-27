<?php

class RouteOrganizer
{


    public $routes;
    public $error_message;





    /**
     * Initiates the routes
     */
    public function listRoutes() 
    {
        $curl = curl_init(Config::$strava_api_url."/athletes/{$_SESSION['athlete']->id}/routes");
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ["Accept: application/json", "Authorization: Bearer {$_SESSION['access_token']}"]
        ]);
        $result = curl_exec($curl);
        if ( ($statusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE)) == 200 ) {
            $this->routes = json_decode($result);
            $_SESSION['folders'] = $this->processRoutes();
        }
        else {
            $this->error_message = "Oops, something went wrong at the route organization (Error $statusCode from Strava). Please try it again later!";
        }

        /*$this->folders = [
            htmlspecialchars("route 1") => (object)['name' => 'Route 1', 'type' => 1, 'distance' => 70, "url" => "https://strava.com"],
            "folder_1" => [
                "route2" => (object)['name' => 'Route 2', 'type' => 2, 'distance' => 120, "url" => "https://strava.com"],
                "folder2" => [
                    "folder_3" => [],
                    "folder_4" => []
                ]
            ]
        ];*/
    }






    /**
     * Transforms the array with routes delivered by strava into folders
     */
    private function processRoutes() 
    {
        $folders = [];
        foreach ($this->routes as $route) {
            $folders[htmlspecialchars($route->name)] = (object)['name' => $route->name, 'type' => 1, 'distance' => 70, "url" => "https://strava.com"];
        }
        return $folders;
    }





    /**
     * Searching all routes of an URI
     * @param string $uri The URI of the request
     * @return bool|object false if the URI is invalid or {items=>[], $level=>[], $breadcrumbs=>[]}
     */
    public function getRoutesOfUri($uri) 
    {
        // Split the URI into an array
        $uriParts = explode('/', substr($uri, 1));
        $level = $_SESSION['folders'];

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
    public static function output404() 
    {
        echo '<h1>Error 404 - This page does not exists!</h1>';
    }




    /**
     * Checks if the user is already authenticated
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
     * Verify the oAuth request, exchange tokens with Strava and redirects to the startpage
     * @param string $code Authorization code from Strava
     * @param string $scope Defined scope
     * @return bool Returns false or redirect to start
     */
    public function tokenExchange($code, $scope) 
    { 
        if ( in_array(Config::$strava_scope, explode(',', $scope)) ) {
            $clientId = Secret::CLIENT_ID;
            $secret = Secret::CLIENT_SECRET;
            $postData = [
                "client_id" => $clientId,
                "client_secret" => $secret,
                "code" => $code,
                "grant_type" => "authorization_code"
            ];

            $curl = curl_init(Config::$strava_token_url);
            curl_setopt_array($curl, [
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => http_build_query($postData),
                CURLOPT_HTTPHEADER => ["Accept: application/json"]
            ]);
            $result = curl_exec($curl);
            
            if ( ($statusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE)) == 200 ) {
                $result = json_decode($result);
                $_SESSION['athlete'] = $result->athlete;
                $_SESSION['access_token'] = $result->access_token;
                $_SESSION['expiration'] = $result->expires_at;
                $_SESSION['refresh_token'] = $result->refresh_token;

                $this->listRoutes();
                header("Location: ".Config::$baseUrl);
            }
            else {
                $this->error_message = "Oops, something went wrong (Error $statusCode). Please try it again later!".$result;
                return false;
            }

        }
        else {
            $this->error_message = "To use this app you must allow RouteOrganizer to access your routes!";
            return false;
        }
    }


}