<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2025
     */   

    // Files of the folder to ignore
    $ignore_models = array(
        'autoload.php',
        'ddbb.php'
    );
    // I add classes that are prioritized in order
    $priority_models = array(
        'model.php',
        'app-model.php',
        'admin-model.php'
    );
    foreach($priority_models as $value) {
        include SERVER_PATH.'/app/models/'.$value;
    }
    // I automatically include each model
    $scandir = scandir(SERVER_PATH.'/app/models');
    $files = array_diff($scandir, array('.', '..'), $ignore_models, $priority_models);
    foreach($files as $value) {
        include SERVER_PATH.'/app/models/'.$value;
    }

?>