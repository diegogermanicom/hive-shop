<?php

    /**
     * @author Diego Martín
     * @copyright Hive®
     * @version 1.0
     * @lastUpdated 2025
     */

    // I make sure that the necessary constant is declared
    Utils::checkDefined('CONTROLLERS_PATH');
    $classBefore = get_declared_classes();
    // I add files that are prioritized in order
    $priorityControllers = array();
    foreach($priorityControllers as $value) {
        if(file_exists(CONTROLLERS_PATH.'/'.$value)) {
            require_once CONTROLLERS_PATH.'/'.$value;
        } else {
            Utils::error('The priority controller file you are trying to load <b>'.$value.'</b> does not exist.');
        }
    }
    // I add classes that I will not load
    $ignoreControllers = array();
    // I automatically include each controller
    $scandir = scandir(CONTROLLERS_PATH);
    $files = array_diff($scandir, array('.', '..'), $ignoreControllers, $priorityControllers);
    foreach($files as $value) {
        require_once CONTROLLERS_PATH.'/'.$value;
    }
    //I ignore system controllers
    $ignoreControllers = array();
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
    return $arrayControllers;

?>