<?php 

    foreach ( $_SESSION['routes'] as $folder=>$route ) {
        echo "$folder<br>";
        print_r($route);
        echo '<br><br>';
    }