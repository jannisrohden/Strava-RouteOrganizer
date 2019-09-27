<?php

class Config {
    static $baseUrl = "http://localhost";
    static $imgUrl = "http://localhost/assets/images";
    static $cssUrl = "http://localhost/assets/css";

    static $mode = "development";
    static $version = "0.1";

    static $strava_auth_url = "https://www.strava.com/oauth/authorize";
    static $strava_token_url = "https://www.strava.com/oauth/token";
    static $strava_api_url = "https://www.strava.com/api/v3";
    static $strava_response_type = "code";
    static $strava_scope = "read_all";
    static $strava_approval_prompt = "auto";

    static $blockedRoutes = ["assets"];

}