<?php

    include __DIR__.'/config.php';
    include __DIR__.'/autoload.php';

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
            if ($result = RouteOrganizer::getRoutes($_SERVER['REQUEST_URI'])) {
                foreach ($result as $item) {
                    if (gettype($item) == 'array') {
                    ?>
                        <a href="<?=key($item)?>"><?=key($item)?></a>
                    <?php
                    }
                    else {
                        echo "$item";
                    }
                }
            }
            else {
                RouteOrganizer::output404();
            }
        ?>
    </main>

    <?php include 'components/footer.php' ?>

</body>
</html>