<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2024
     */

    // Load config file
    include __DIR__.'/app/config/config.php';
    // Init framework
    include __DIR__.'/app/config/init.php';

    // Load model objects
    include MODELS_PATH.'/autoload.php';
    // Load controler objects
    include CONTROLLERS_PATH.'/autoload.php';   
    // Load routes list
    include ROUTES_PATH.'/autoload.php';

    // Have fun!

?>