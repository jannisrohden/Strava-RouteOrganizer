<header class="header">

    <a href="/" class="header__branding dark-hover">
        <img src="<?= $_ENV['ASSET_URL'] ?>/images/logo/white.svg" alt="RouteOrganizer Logo" class="header__branding__image">
        <span>RouteOrganizer</span>
    </a>

    <?php if (RouteOrganizer::isUserLoggedIn()) : ?>
        <a href="https://strava.com" target="_blank" class="header__profile dark-hover">
            <img src="" alt="" class="header__profile__image">
            Jannis Rohden
        </a>
    <?php else : ?>
        <a href="https://strava.com" target="_blank" class="strava-logo dark-hover">
            <img src="<?= $_ENV['ASSET_URL'] ?>/images/strava.png" alt="Powered by Strava">
        </a>
    <?php endif; ?>

</header>