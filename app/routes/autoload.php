<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2025
    */   

    // First I create the route object
    $R = new Route();
    // Files of the folder to ignore
    $ignore_routes = array(
        'autoload.php'
    );
    // I add routes that are prioritized in order
    $priority_routes = array();
    foreach($priority_routes as $value) {
        include ROUTES_PATH.'/'.$value;
    }
    // I automatically include each route
    $scandir = scandir(ROUTES_PATH);
    $files = array_diff($scandir, array('.', '..'), $ignore_routes);
    foreach($files as $value) {
        $R->reset();
        include ROUTES_PATH.'/'.$value;
    }
    // No route found
    $R->empty();

?>