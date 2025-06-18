<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2025
    */

    // I make sure that the necessary constant is declared
    Utils::checkDefined('ROUTES_PATH');
    // I add classes that are prioritized in order
    $priorityRoutes = array();
    foreach($priorityRoutes as $value) {
        if(file_exists(ROUTES_PATH.'/'.$value)) {
            require_once ROUTES_PATH.'/'.$value;
        } else {
            Utils::error('The priority route file you are trying to load <b>'.$value.'</b> does not exist.');
        }
    }
    // I add classes that I will not load
    $ignoreRoutes = array();
    // I automatically include each route
    $scandir = scandir(ROUTES_PATH);
    $files = array_diff($scandir, array('.', '..'), $ignoreRoutes);
    foreach($files as $value) {
        $R->reset();
        require_once ROUTES_PATH.'/'.$value;
    }

?>