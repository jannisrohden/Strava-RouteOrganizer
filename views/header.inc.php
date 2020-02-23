<header class="header">

    <a href="/" class="header__branding">
        <img src="<?= $_ENV['ASSET_URL'] ?>/images/logo/white.svg" alt="RouteOrganizer Logo" class="header__branding__image">
        <span>RouteOrganizer</span>
    </a>

    <?php if (Strava::isUserLoggedIn()) : ?>
        <a href="https://strava.com" target="_blank" class="header__profile">
            <img src="<?= $_SESSION['strava']['image'] ?>" alt="" class="header__profile__image">
            <?= $_SESSION['strava']['name'] ?>
        </a>
    <?php else : ?>
        <a href="https://strava.com" target="_blank" class="strava-logo">
            <img src="<?= $_ENV['ASSET_URL'] ?>/images/strava.png" alt="Powered by Strava">
        </a>
    <?php endif; ?>

</header>