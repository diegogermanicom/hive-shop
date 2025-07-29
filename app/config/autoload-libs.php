<?php

    /**
     * @author Diego Martín
     * @copyright Hive®
     * @version 1.0
     * @lastUpdated 2025
     */

    $libsPath = __DIR__.'/../libs';
     // I add classes that are prioritized in order
    $priorityLibs = array();
    foreach($priorityLibs as $value) {
        if(file_exists($libsPath.'/'.$value)) {
            require_once $libsPath.'/'.$value;
        } else {
            Utils::error('The priority library file you are trying to load <b>'.$value.'</b> does not exist.');
        }
    }
    // I add classes that I will not load
    $ignoreLibs = array();
    // I automatically include each library
    $scandir = scandir($libsPath);
    $files = array_diff($scandir, array('.', '..'), $ignoreLibs, $priorityLibs);
    foreach($files as $value) {
        require_once $libsPath.'/'.$value;
    }

?>