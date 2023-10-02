<?php

    // Files of the folder to ignore
    $ignore_routes = array(
        '.',
        '..',
        'index.html',
        'autoload.php'
    );
    // First I create the route object
    $R = new Route();
    // I automatically include each model
    $files = scandir(SERVER_PATH.'/app/routes');
    for($i = 0; $i < count($files); $i++) {
        if(!in_array($files[$i], $ignore_routes)) {
            include SERVER_PATH.'/app/routes/'.$files[$i];
        }
    }
    // No route found
    $R->empty();

?>