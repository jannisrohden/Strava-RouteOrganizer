<?php

    require __DIR__.'/config.php';
    require __DIR__.'/secret.php';

    spl_autoload_register(function ($classname) {
        include __DIR__."/src/$classname.php";
    });