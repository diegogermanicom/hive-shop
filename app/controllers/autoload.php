<?php

    // Files of the folder to ignore
    $ignore_controllers = array(
        '.',
        '..',
        'index.html',
        'autoload.php',
        'controller.php'
    );
    // First I include the controller parent class
    include SERVER_PATH.'/app/controllers/controller.php';
    // I automatically include each controller
    $files = scandir(SERVER_PATH.'/app/controllers');
    for($i = 0; $i < count($files); $i++) {
        if(!in_array($files[$i], $ignore_controllers)) {
            include SERVER_PATH.'/app/controllers/'.$files[$i];
        }
    }

?>