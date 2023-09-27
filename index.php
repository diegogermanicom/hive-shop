<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2023
     */

    // Load config file
    include __DIR__.'/app/config/config.php';
    // Init framework
    include __DIR__.'/app/config/init.php';

    // Load model objects
    include SERVER_PATH.'/app/models/autoload.php';
    // Load controler objects
    include SERVER_PATH.'/app/controllers/autoload.php';    
    // Load routes list
    include SERVER_PATH.'/app/routes/autoload.php';

    // Have fun!

?>