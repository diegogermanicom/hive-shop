<?php

    // Files of the folder to ignore
    $ignore_controllers = array(
        'index.html',
        'autoload.php'
    );
    // I add classes that are prioritized in order
    $priority_controllers = array(
        'controller.php'
    );
    foreach($priority_controllers as $value) {
        include SERVER_PATH.'/app/controllers/'.$value;
    }
    // I automatically include each controller
    $scandir = scandir(SERVER_PATH.'/app/controllers');
    $files = array_diff($scandir, array('.', '..'), $ignore_controllers, $priority_controllers);
    foreach($files as $value) {
        include SERVER_PATH.'/app/controllers/'.$value;
    }

?>