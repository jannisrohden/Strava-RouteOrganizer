<!DOCTYPE html>
<html lang="en">
<head>
    <title>Strava RouteOrganizer</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="<?= Config::$cssUrl ?>/master.css">
</head>
<body>
    
    <header class="header">

        <a href="<?= Config::$baseUrl ?>" class="header__branding">
            <img src="<?= Config::$imgUrl ?>/logo/white.svg" alt="Logo" class="header__branding__logo">
            <p class="header__branding__name">RouteOrganizer</p>
        </a>

        <?php if ($athlete): ?>
            <a href="https://strava.com/athletes/<?= $athlete->id ?>" target="_blank" class="header__profile">
                <span class="header__profile__name">
                    <?= $athlete->firstname ?> <?= $athlete->lastname ?>
                </span>
                <img src="<?= $athlete->profile_medium ?>" alt="<?= $athlete->username ?>" class="header__profile__image">
            </a>
        <?php else: ?>
            <a href="https://strava.com" target="_blank" class="header__strava">
                <img src="<?= Config::$imgUrl ?>/strava/light/horizontal.svg" alt="This App is powered by Strava" class="header__strava__logo">
            </a>
        <?php endif; ?>

    </header>

    <main class="main">
        <?php 
            foreach ($components as $component) include $component;
        ?>
    </main>

    <footer class="footer">
        <a href="https://strava.com" target="_blank" class="footer__strava">
            <img src="<?= Config::$imgUrl ?>/strava/light/horizontal.svg" alt="This App is powered by Strava" class="footer__strava__logo">
        </a>
    </footer>

</body>
</html>