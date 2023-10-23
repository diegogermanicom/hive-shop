<?php

    // Files of the folder to ignore
    $ignore_routes = array(
        'index.html',
        'autoload.php'
    );
    // First I create the route object
    $R = new Route();
    // I automatically include each model
    $scandir = scandir(SERVER_PATH.'/app/routes');
    $files = array_diff($scandir, array('.', '..'), $ignore_routes);
    foreach($files as $value) {
        include SERVER_PATH.'/app/routes/'.$value;
    }
    // No route found
    $R->empty();

?>