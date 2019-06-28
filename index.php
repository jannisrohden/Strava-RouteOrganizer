<?php

    include __DIR__.'/config.php';
    include __DIR__.'/autoload.php';

    $organizer = new RouteOrganizer;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'components/meta.php' ?>
    <title>RouteOrganizer for your Strava routes</title>
</head>
<body>
    
    <?php include 'components/header.php' ?>

    <main class="main">
        <?php
            // When User is logged in
            if (Strava::loggedIn()) {

                // Get all items of the actual folder
                if ($items = $organizer->getRoutes($_SERVER['REQUEST_URI'])) {
                    print_r($items);
                    foreach ($items as $item) {
                        echo key($item)."<br/>";
                        /*if (gettype($item) == 'array') {
                            ?>
                                <a href="<?=key($item) ?>"><?=key($item) ?></a>
                            <?php
                        }
                        elseif (gettype($item) == 'object') {
                            ?> 
                                <a href="<?= $item->url ?>"><?= $item->name ?></a>
                            <?php
                        }*/
                    }
                }
                // Folder is not existing
                else {
                    $organizer->output404();
                }

            }
            else {
                include __DIR__.'/views/auth.php';
            }
        ?>
    </main>

    <?php include 'components/footer.php' ?>

</body>
</html>