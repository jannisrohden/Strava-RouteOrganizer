<header class="header">

    <a href="<?= Config::$baseUrl ?>" class="header__branding">
        <img src="<?= Config::$imgUrl ?>/logo/white.svg" alt="Logo" class="header__branding__logo">
        <p class="header__branding__name">RouteOrganizer</p>
    </a>

    <?php if (RouteOrganizer::isUserLoggedIn()): ?>
        <a href="https://strava.com/athletes/<?= $_SESSION['athlete']->id ?>" target="_blank" class="header__profile">
            <span class="header__profile__name">
                <?= $_SESSION['athlete']->firstname ?> <?= $_SESSION['athlete']->lastname ?>
            </span>
            <img src="<?= $_SESSION['athlete']->profile_medium ?>" alt="<?= $_SESSION['athlete']->username ?>" class="header__profile__image">
        </a>
    <?php else: ?>
        <a href="https://strava.com" target="_blank" class="header__strava">
            <img src="<?= Config::$imgUrl ?>/strava/light/horizontal.svg" alt="This App is powered by Strava" class="header__strava__logo">
        </a>
    <?php endif; ?>

</header>