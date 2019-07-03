<?php


// Route does not exist
if (!$result = $organizer->getRoutes($_SERVER['REQUEST_URI'])) {
    $organizer->output404();
}
// Route is valid
else {

    // Output Breadcrumbs
    $breadcrumbRoute = "";
    foreach ($result->breadcrumbs as $crumb) {
        $breadcrumbRoute .= "/$crumb";
        $breadcrumbs = "<a href='$breadcrumbRoute' class='output__breadcrumbs__item'>$crumb</a>";
    }

        // Output routes and folders
        $folders = $routes = "";
        foreach ($result->items as $item) {
            $value = $result->level[$item];

            # Output route
            if (gettype($value) == 'object') {
                $routes .= "<a href='$value->url' class='route' target='_blank'>$value->name</a>";
            }
            # Output folder
            elseif (gettype($value) == 'array') {
                $folders .= "<a href='$item' class='folder'>$item</a>";
            }

        }
}
?>


<div class="output__breadcrumbs">
    <a href="/" class="output__breadcrumbs__item">/</a>
    <?= $breadcrumbs ?>
</div>

<div class="output__listing">
    <div class="output__listing__row">
        <?= $folders ?>
    </div>

    <div class="output__listing__row">
        <?= $routes ?>
    </div>
</div>