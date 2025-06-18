<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2025
    */

    // I make sure that the necessary constant is declared
    Utils::checkDefined('MODELS_PATH');
    // I add classes that are prioritized in order
    $priorityModels = array(
        'app-model.php',
        'admin-model.php'
    );
    foreach($priorityModels as $value) {
        if(file_exists(MODELS_PATH.'/'.$value)) {
            require_once MODELS_PATH.'/'.$value;
        } else {
            Utils::error('The priority model file you are trying to load <b>'.$value.'</b> does not exist.');
        }
    }
    // I add classes that I will not load
    $ignoreModels = array();
    // I automatically include each model
    $scandir = scandir(MODELS_PATH);
    $files = array_diff($scandir, array('.', '..'), $ignoreModels, $priorityModels);
    foreach($files as $value) {
        require_once MODELS_PATH.'/'.$value;
    }

?>
