<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2025
     */   

    // Files of the folder to ignore
    $ignore_routes = array(
        'autoload.php'
    );
    // First I create the route object
    $R = new Route();
    // I automatically include each model
    $scandir = scandir(SERVER_PATH.'/app/routes');
    $files = array_diff($scandir, array('.', '..'), $ignore_routes);
    foreach($files as $value) {
        $R->reset();
        include SERVER_PATH.'/app/routes/'.$value;
    }
    // No route found
    $R->empty();

?>