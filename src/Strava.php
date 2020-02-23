<?php

use Symfony\Component\HttpClient\HttpClient as HttpClient;

class Strava
{



    private string $clientId, $secret;



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
        if (
            isset($_SESSION['strava']['access_token'], $_SESSION['strava']['id'], $_SESSION['strava']['expiration']) &&
            $_SESSION['strava']['expiration'] > time()
        ) {
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



    // Compares the returned state
    private function validateState ( string $state ): bool
    {
        if ( $state === $_SESSION['strava']['state'] ) 
            return true;
        return false;
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
    private function showGeneralError ( int $code ): void
    {
        RouteOrganizer::showError("An error occurred ($code). Please try it again later!");
    }



    // Requests an access token from Strava
    private function requestAccessToken ( string $code ): void
    {
        $client = HttpClient::create();
        $response = $client->request('POST', $_ENV['STRAVA_API_URL']."/oauth/token", [
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
            $this->showGeneralError(2);
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
            $_SESSION['strava'] = [
                'expiration' => $res['expires_at'],
                'refresh_token' => $res['refresh_token'],
                'access_token' => $res['access_token'],
                'id' => $res['athlete']['id'],
                'name' => $res['athlete']['firstname'] . ' ' . $res['athlete']['lastname'],
                'image' => $res['athlete']['profile_medium']
            ];
            header("Location: {$_ENV['BASE_URL']}");
        }
        else
            $this->showGeneralError(3);
    }

}