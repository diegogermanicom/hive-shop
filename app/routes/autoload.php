<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2025
    */   

    // Files of the folder to ignore
    $ignoreRoutes = array(
        'autoload.php'
    );
    // I add routes that are prioritized in order
    $priorityRoutes = array();
    foreach($priorityRoutes as $value) {
        if(file_exists(ROUTES_PATH.'/'.$value)) {
            include ROUTES_PATH.'/'.$value;
        } else {
            Utils::error('The priority route file you are trying to load <b>'.$value.'</b> does not exist.');
        }
    }
    // I automatically include each route
    $scandir = scandir(ROUTES_PATH);
    $files = array_diff($scandir, array('.', '..'), $ignoreRoutes);
    foreach($files as $value) {
        $R->reset();
        include ROUTES_PATH.'/'.$value;
    }
    // No route found
    $R->empty();

?>