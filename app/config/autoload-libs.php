<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2025
    */

    Utils::checkDefined('LIBS_PATH');
    // I add classes that are prioritized in order
    $priorityLibs = array();
    foreach($priorityLibs as $value) {
        if(file_exists(LIBS_PATH.'/'.$value)) {
            require_once LIBS_PATH.'/'.$value;
        } else {
            Utils::error('The priority library file you are trying to load <b>'.$value.'</b> does not exist.');
        }
    }
    // I add classes that I will not load
    $ignoreLibs = array(
        'utils.php'
    );
    // I automatically include each library
    $scandir = scandir(LIBS_PATH);
    $files = array_diff($scandir, array('.', '..'), $ignoreLibs, $priorityLibs);
    foreach($files as $value) {
        require_once LIBS_PATH.'/'.$value;
    }

?>