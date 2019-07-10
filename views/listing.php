<?php


// Route does not exist
if (!$result = $organizer->getRoutes($_SERVER['REQUEST_URI'])) {
    $organizer->output404();
}
// Route is valid
else {

    // Generate Breadcrumbs
    $breadcrumbRoute = $breadcrumbs = "";
    foreach ($result->breadcrumbs as $crumb) {
        $breadcrumbRoute .= "/$crumb";
        $breadcrumbs .= "<span class='output__breadcrumbs__line'>/</span>";
        $breadcrumbs .= "<a href='$breadcrumbRoute' class='output__breadcrumbs__item'>$crumb</a>";
    }

    // Generate routes and folders
    $folders = $routes = "";
    foreach ($result->items as $item) {
        $value = $result->level[$item];

        $uriPath = $_SERVER['REQUEST_URI'];
        if ($uriPath == "/") $uriPath = "";

        # Output route
        if (gettype($value) == 'object') {
            $routes .= "<a href='$value->url' class='route' target='_blank'>
                $value->name
            </a>";
        }
        # Output folder
        elseif (gettype($value) == 'array') {
            $folders .= "<a href='$uriPath/$item' class='folder'>
                <img src='".Config::$imgUrl."/icons/folder.svg' class='folder__image' />
                ".htmlspecialchars($item)."
            </a>";
        }

    }
}
?>


<?php if (isset($breadcrumbs)): ?>
    <div class="output__breadcrumbs">
        <a href="/" class="output__breadcrumbs__item">Home</a>
        <?= $breadcrumbs ?>
    </div>
<?php endif; ?>

<?php if (isset($folders, $routes)): ?>
    <div class="output__listing">
        <div class="output__listing__row">
            <?= $folders ?>
        </div>

        <div class="output__listing__row">
            <?= $routes ?>
        </div>
    </div>

    <?php if ($folders.$routes == ""): ?>
        <p class="output__headline">Nothing to see here - This folder is empty</p>
    <?php endif; ?>

<?php endif; ?>
