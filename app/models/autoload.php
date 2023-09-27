<?php

    // Files of the folder to ignore
    $ignore_models = array(
        '.',
        '..',
        'index.html',
        'autoload.php'
    );
    // I add classes that are prioritized in order
    $priority_models = array(
        'model.php',
        'app-model.php',
        'admin-model.php'
    );
    foreach($priority_models as $i => $value) {
        include SERVER_PATH.'/app/models/'.$value;
    }
    // I automatically include each model
    $files = scandir(SERVER_PATH.'/app/models');
    for($i = 0; $i < count($files); $i++) {
        if(!in_array($files[$i], $ignore_models) && !in_array($files[$i], $priority_models)) {
            include SERVER_PATH.'/app/models/'.$files[$i];
        }
    }

?>