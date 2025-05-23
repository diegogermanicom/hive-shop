<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2025
    */   

    // Files of the folder to ignore
    $ignore_models = array(
        'autoload.php'
    );
    // I add classes that are prioritized in order
    $priority_models = array(
        'app-model.php',
        'admin-model.php'
    );
    foreach($priority_models as $value) {
        include MODELS_PATH.'/'.$value;
    }
    // I automatically include each model
    $scandir = scandir(MODELS_PATH);
    $files = array_diff($scandir, array('.', '..'), $ignore_models, $priority_models);
    foreach($files as $value) {
        include MODELS_PATH.'/'.$value;
    }

?>