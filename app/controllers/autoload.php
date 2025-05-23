<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2025
    */

    $classBefore = get_declared_classes();

    // Files of the folder to ignore
    $ignoreFile = array(
        'autoload.php'
    );
    // I add files that are prioritized in order
    $priorityFiles = array();
    foreach($priorityFiles as $value) {
        include CONTROLLERS_PATH.'/'.$value;
    }
    // I automatically include each controller
    $scandir = scandir(CONTROLLERS_PATH);
    $files = array_diff($scandir, array('.', '..'), $ignoreFile, $priorityFiles);
    foreach($files as $value) {
        include CONTROLLERS_PATH.'/'.$value;
    }

    //I ignore system controllers
    $ignoreControllers = array(
        'Err'
    );
    foreach($ignoreControllers as $value) {
        array_push($classBefore, $value);
    }
    // I save the name of all the created controllers
    $classAfter = get_declared_classes();
    $arrayControllers = array_values(array_diff($classAfter, $classBefore));
    // Now I save the functions of each controller
    foreach($arrayControllers as $index => $value) {
        $arrayControllers[$index] = array(
            'name' => $value,
            'functions' => get_class_methods($value)
        );
    }
    define('CONTROLLERS', $arrayControllers);

?>