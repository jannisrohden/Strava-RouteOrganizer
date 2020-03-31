<?php

use Symfony\Component\HttpClient\HttpClient as HttpClient;

class Strava
{



    private string $clientId, $secret;
    private $client;



    // Arranges the initial URL for the oAuth process
    public static function getAuthorizationUrl(): string
    {
        $_SESSION['strava']['state'] = substr(strval(time()), -6, 6) . rand(100000, 999999);

        $r = $_ENV['STRAVA_AUTHORIZATION_URL'];
        $r .= "?client_id={$_ENV['STRAVA_CLIENT_ID']}";
        $r .= "&redirect_uri={$_ENV['AUTH_REDIRECT_URI']}";
        $r .= "&response_type=code";
        $r .= "&approval_prompt=auto";
        $r .= "&scope=read";
        $r .= "&state={$_SESSION['strava']['state']}";

        return $r;
    }



    // Checks if strava's information are complete
    public static function isUserLoggedIn(): bool
    {
        $val = isset(
            $_SESSION['strava']['access_token'], 
            $_SESSION['strava']['id'], 
            $_SESSION['strava']['expiration'],
            $_SESSION['routes']
        );
        if ( $val && $_SESSION['strava']['expiration'] > time() ) {
            return true;
        }
        return false;
    }



    // Checks if only session is expired
    public static function wasUserLoggedIn(): bool
    {
        if (
            isset($_SESSION['strava']['access_token'], $_SESSION['strava']['id'], $_SESSION['strava']['expiration']) &&
            $_SESSION['strava']['expiration'] < time()
        ) {
            return true;
        }
        return false;
    }



    // Creates an instance
    public function __construct ( string $clientId, string $secret )
    {
        $this->clientId = $clientId;
        $this->secret = $secret;
        $this->client = HttpClient::create();
    }



    // Validates the returned information and starts the access token request
    public function authenticate ( string $code, string $scope, string $state ): void
    {
        if ( $this->validateScope($scope) ) {
            $this->requestAccessToken($code);
        }
        else 
            $this->showPermissionError();
    }



    // Checks if all permissions are given
    private function validateScope ( string $scope ): bool
    {
        if ( $scope == 'read' || strpos($scope, 'read') !== false )
            return true;
        return false;
    }



    // Displays a notification that permissions are missing
    private function showPermissionError(): void
    {
        RouteOrganizer::showError('Please give RouteOrganizer the permission to read your routes!');
    }



    // Outputs a general error message
    private function showError (string $message = "An error occurred. Please try it again later!" ): void
    {
        RouteOrganizer::showError($message);
    }



    // Requests an access token from Strava
    private function requestAccessToken ( string $code ): void
    {
        $response = $this->client->request('POST', $_ENV['STRAVA_API_URL']."/oauth/token", [
            'body' => [
                'client_id' => $this->clientId, 
                'client_secret' => $this->secret, 
                'code' => $code, 
                'grant_type' => 'authorization_code'
            ]
        ]);

        if ( $response->getStatusCode() === 200 ) 
            $this->validateAccessToken($response->toArray());
        else
            $this->showError("An error ({$response->getStatusCode()}) during the communication with Strava occurred! Please try it again");
    }



    // Validates the response from the access token request
    private function validateAccessToken ( array $res ): void
    {
        $requiredValues = [
            isset($res['expires_at']),
            isset($res['refresh_token']),
            isset($res['access_token']),
            isset($res['athlete']['id']),
            isset($res['athlete']['firstname']),
            isset($res['athlete']['lastname'])
        ];
        if ( !in_array(false, $requiredValues) ) {
            if ( $this->createAuthSession($res) ) {
                if ( $this->loadRoutes() ) {
                    header("Location: {$_ENV['BASE_URL']}?");
                }
                else {
                    $this->showError("Failed to load your routes from Strava. Please try it again");
                }
            }
        }
        else
            $this->showError();
    }



    private function createAuthSession( array $res ): bool
    {
        $_SESSION['strava'] = [
            'expiration' => $res['expires_at'],
            'refresh_token' => $res['refresh_token'],
            'access_token' => $res['access_token'],
            'id' => $res['athlete']['id'],
            'name' => $res['athlete']['firstname'] . ' ' . $res['athlete']['lastname'],
            'image' => $res['athlete']['profile_medium']
        ];
        return true;
    }



    private function loadRoutes(): bool
    {
        $url = $_ENV['STRAVA_API_URL'] . "/athletes/{$_SESSION['strava']['id']}/routes?per_page=200&page=1";
        $response = $this->client->request( 'GET',  $url, [
                'auth_bearer' => $_SESSION['strava']['access_token']
        ]);
        if ( $response->getStatusCode() == 200 ) {
            return $this->listRoutes($response->toArray());
        }
        else {
            return false;
        }
    }



    private function listRoutes ( array $routes ): bool
    {
        $_SESSION['routes'] = [];
        foreach ( $routes as $route ) {
            $getRouteFolders = $this->getRouteFolders($route['name'], []);
            $route['name'] = array_keys($getRouteFolders)[0];
            $this->putRouteInFolder($route, $getRouteFolders[$route['name']]);
        }
        return true;
    }


    
    private function getRouteFolders ( string $routeName, array $folders ): array
    {
        if ($close = strpos($routeName, ']')) {
            $folders[] = str_replace('[', '', substr($routeName, 0, $close));
            $newName = substr($routeName, $close + 1);
            return $this->getRouteFolders($newName, $folders);
        }
        else 
            return [ $routeName => $folders ];
    }



    private function putRouteInFolder ( array $route, array $folders ): void
    {
        $target = '';
        foreach ( $folders as $folder ) {
            $target .= '/'.$this->parseFolderName($folder);
        }

        if ( !isset($_SESSION['routes'][$target]) )
            $_SESSION['routes'][$target] = [];

        $_SESSION['routes'][$target][] = (object)[
            'name' => $route['name'],
            'type' => $route['type'],
            'distance' => $route['distance'],
            'id' => $route['id'],
            'elevation' => $route['elevation_gain']
        ];
    }



    private function parseFolderName ( string $name ): string
    {
        $name = trim($name);
        $name = strtolower($name);
        $name = htmlspecialchars($name);
        $replace = [
            ' ' => '_',
            /*'ö' => 'oe',
            'ä' => 'ae',
            'ü' => 'ue',
            'ß' => 'ss',*/
            '&' => '+',
            '?' => ' '
        ];
        $name = str_replace(array_keys($replace), array_values($replace), $name);
        return $name;
    }



}