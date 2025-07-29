<?php

    /**
     * @author Diego Martín
     * @copyright Hive®
     * @version 1.0
     * @lastUpdated 2025
     */

    $routesPath = __DIR__.'/../routes';
    // I add classes that are prioritized in order
    $priorityRoutes = array();
    foreach($priorityRoutes as $value) {
        if(file_exists($routesPath.'/'.$value)) {
            require_once $routesPath.'/'.$value;
        } else {
            Utils::error('The priority route file you are trying to load <b>'.$value.'</b> does not exist.');
        }
    }
    // I add classes that I will not load
    $ignoreRoutes = array();
    // I automatically include each route
    $scandir = scandir($routesPath);
    $files = array_diff($scandir, array('.', '..'), $ignoreRoutes);
    foreach($files as $value) {
        $R->reset();
        require_once $routesPath.'/'.$value;
    }

?>