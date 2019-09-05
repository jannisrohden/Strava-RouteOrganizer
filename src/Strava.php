<?php

class Strava
{

    public $error_message;




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