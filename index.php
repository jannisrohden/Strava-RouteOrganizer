<?php
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
        <div class="output">
            <?php
                // When User is logged in
                if (Strava::loggedIn()) {
                    include __DIR__.'/views/listing.php';
                }
                else {
                    include __DIR__.'/views/auth.php';
                }
            ?>
        </div>
    </main>

    <?php include 'components/footer.php' ?>

</body>
</html>