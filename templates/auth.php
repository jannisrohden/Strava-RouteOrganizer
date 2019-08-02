<div class="infobox">
    <p class="infobox__headline">Please connect with Strava</p>
    <p class="infobox__text">After signing in to Strava you will see all your routes sorted by folders. To add a route to a folder just use the schema <b><i>[folder1][folder2] Route Name</i></b> for your routes name. You can use as many folders as you want.</p>

    <form action="<?= Config::$strava_auth_url ?>" method="get">

        <input type="hidden" name="client_id" value="<?= Secret::CLIENT_ID ?>">
        <input type="hidden" name="redirect_uri" value="<?= Config::$baseUrl ?>">
        <input type="hidden" name="response_type" value="<?= Config::$strava_response_type ?>">
        <input type="hidden" name="scope" value="<?= Config::$strava_scope ?>">
        <input type="hidden" name="approval_prompt" value="<?= Config::$strava_approval_prompt ?>">

        <button type="submit" class="infobox__button infobox__button--orange">Connect with Strava</button>
    </form>
    
</div>