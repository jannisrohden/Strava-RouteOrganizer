<?php

class Strava
{

    private $logggedIn = false;
    private $bearer;
    private $expiration;
    private $refresh_token;
    public $error_message;



    /**
     * Checks if the user is already authenticated
     * @return bool
     */
    public function isUserLoggedIn() {
        /*if ($this->logggedIn) {
            return true
        }
        else {
            if (isset($_COOKIE[]))
        }*/
        return $this->logggedIn;
    }



    /**
     * Verify the oAuth request, exchange tokens with Strava and redirects to the startpage
     * @param string $code Authorization code from Strava
     * @param string $scope Defined scope
     * @return bool Returns false or redirect to start
     */
    public function tokenExchange($code, $scope) { 
        echo "der Code ist: $code";
        if (in_array(Config::$strava_scope, explode(',', $scope))) {
            $clientId = Secret::CLIENT_ID;
            $secret = Secret::SECRET_TOKEN;
            $postData = [
                "client_id" => $clientId,
                "client_secret" => $secret,
                "code" => $code,
                "grant_type" => "authorization_code"
            ];
            $httpContent = http_build_query($postData);

            $curl = curl_init(Config::$strava_token_url);
            curl_setopt_array($curl, [
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $httpContent,
            ]);
            $result = curl_exec($curl);
            
            if (($statusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE)) == 200) {
                $result = json_decode($result);
                $this->athlete = $result->athlete;
                $this->bearer = $result->access_token;
                $this->expiration = $result->expires_at;
                $this->refresh_token = $result->refresh_token;
                header("Location: {Config::$baseUrl}");
            }
            else {
                $this->error_message = "Oops, something went wrong (Error $statusCode). Please try it again later!".$result;
                print_r($httpContent);
                return false;
            }

        }
        else {
            $this->error_message = "To use this app you must allow RouteOrganizer to access your routes!";
            return false;
        }
    }



}