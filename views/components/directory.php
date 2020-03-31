<?php

$showFolders = [];
$showRoutes = [];

foreach ($_SESSION['routes'] as $folder => $routes) {

    $folderParts = explode('/', $folder);
    $urlParts = explode('/', $_SERVER['REQUEST_URI']);

    if ($folder == '')
        $folder = '/';

    if ($folder == $_SERVER['REQUEST_URI'])
        $showRoutes = $routes;
    else if (isset($folderParts[count($folderParts) - 2]) && $folderParts[count($folderParts) - 2] == $urlParts[count($urlParts) - 1]) {
        $showFolders[] = str_replace('/', '', $folder);
    }
}

$lastFolder = null;
if ( count($urlParts) > 1 && $_SERVER['REQUEST_URI'] != '' && $_SERVER['REQUEST_URI'] != '/' )
    $lastFolder = $urlParts[count($urlParts)-2];

$showFolders = array_unique($showFolders);

?>

<div class="listing">

    <div class="folders">
        <?php if ( $lastFolder !== null ) : ?>
            <a href="/<?= $lastFolder ?>" class="folders__item">
                <img src="<?= $_ENV['ASSET_URL'] ?>/images/icons/back.svg">
            </a>
        <?php endif; ?>

        <?php foreach ($showFolders as $folder) : ?>
            <a href="/<?= $folder ?>" class="folders__item">
                <img src="<?= $_ENV['ASSET_URL'] ?>/images/icons/folder.svg">
                <?= ucfirst($folder) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="routes">
        <?php foreach ($showRoutes as $route) : ?>
            <div class="routes__item">
                <div class="routes__item__head">
                    <img src="<?= $_ENV['ASSET_URL'] ?>/images/icons/<?= $route->type == 1 ? 'cycling.svg' : 'running.svg' ?>" class="routes__item__icon">
                    <p class="routes__item__small">Distance: <?= round($route->distance / 1000, 2) ?> km</p>
                    <p class="routes__item__small">Elevation: <?= round($route->elevation, 2) ?> m</p>
                </div>
                <div class="routes__item__footer">
                    <p class="routes__item__title"><?= $route->name ?></p>
                </div>
                <div class="routes__item__footer">
                    <a href="https://strava.com/routes/<?= $route->id ?>" target="_blank" class="routes__item__button" title="View details and segments">
                        <img src="<?= $_ENV['ASSET_URL'] ?>/images/icons/eye.svg" alt="View route details">
                        View
                    </a>
                    <a href="https://strava.com/routes/<?= $route->id ?>/edit" target="_blank" class="routes__item__button" title="Edit this route">
                        <img src="<?= $_ENV['ASSET_URL'] ?>/images/icons/edit.svg" alt="View route details">
                        Edit
                    </a>
                    <a href="/export.php?file=gpx&route=<?= $route->id ?>" target=" _blank" class="routes__item__button" title="Download the route as GPX file">
                        <img src="<?= $_ENV['ASSET_URL'] ?>/images/icons/download.svg" alt="View route details">
                        GPX
                    </a>
                    <a href="/export.php?file=tcx&route=<?= $route->id ?>" target="_blank" class="routes__item__button" title="Download the route as TCX file">
                        <img src="<?= $_ENV['ASSET_URL'] ?>/images/icons/download.svg" alt="View route details">
                        TCX
                    </a>
                </div>
            </div>
        <?php endforeach ?>
    </div>

</div>