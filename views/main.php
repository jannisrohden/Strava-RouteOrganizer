<!DOCTYPE html>
<html lang="en">

<head>
    <?php include __DIR__ . '/meta.inc.php'; ?>
    <title>RouteOrganizer | Group your Strava routes in folders</title>
</head>

<body class="<?= !Strava::isUserLoggedIn() ? 'has-image' : '' ?>">
    <?php include __DIR__ . '/header.inc.php'; ?>

    <main class="container">
        <?= $content ?>
    </main>

    <?php include __DIR__ . '/footer.inc.php'; ?>
</body>

</html>