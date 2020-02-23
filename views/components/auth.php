<div class="message-box">
    <h3 class="message-box__headline">Please sign in</h3>
    <p class="message-box__description margin-y">
        <?php if (Strava::wasUserLoggedIn()) : ?>
            Your session expired. Please sign in again to continue. <?= $_SESSION['strava']['expiration'] ?>
        <?php else : ?>
            Sign in with your Strava account to see your routes here.
        <?php endif; ?>
    </p>
    <a href="<?= Strava::getAuthorizationUrl() ?>" class="button button--large button--orange">Sign in with Strava</a>
</div>