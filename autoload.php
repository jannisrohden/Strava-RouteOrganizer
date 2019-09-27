<?php

    require __DIR__.'/Config.php';
    require __DIR__.'/Secret.php';

    spl_autoload_register(function ($classname) {
        include __DIR__."/src/$classname.php";
    });