<div class="infobox">
    <p class="infobox__headline">Please connect with Strava</p>
    <p class="infobox__text infobox__text--center">You must sign in with your Strava account and allow RouteOrganizer to view your routes.</p>

    <form action="<?= Config::$strava_auth_url ?>" method="get">

        <input type="hidden" name="client_id" value="<?= Secret::CLIENT_ID ?>">
        <input type="hidden" name="redirect_uri" value="<?= Config::$baseUrl ?>">
        <input type="hidden" name="response_type" value="<?= Config::$strava_response_type ?>">
        <input type="hidden" name="scope" value="<?= Config::$strava_scope ?>">
        <input type="hidden" name="approval_prompt" value="<?= Config::$strava_approval_prompt ?>">

        <button type="submit" class="infobox__button infobox__button--orange">Connect with Strava</button>
    </form>
    
</div>