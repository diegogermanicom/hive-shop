<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2024
     */

    // App call method
    define('METHOD', strtolower($_SERVER['REQUEST_METHOD']));
    // App call route
    define('ROUTE', strtolower(strtok($_SERVER["REQUEST_URI"], '?')));

    define('ADMIN_PATH', PUBLIC_PATH.'/'.ADMIN_NAME);
    define('SERVER_PATH', $_SERVER['DOCUMENT_ROOT'].PUBLIC_PATH);
    define('EMAILS_PATH', SERVER_PATH.'/app/emails');
    define('LANG_PATH', SERVER_PATH.'/app/langs');
    define('IMG_PATH', SERVER_PATH.'/img');
    define('CONTROLLERS_PATH', SERVER_PATH.'/app/controllers');
    define('MODELS_PATH', SERVER_PATH.'/app/models');
    define('ROUTES_PATH', SERVER_PATH.'/app/routes');
    define('VIEWS_ADMIN', SERVER_PATH.'/app/views/admin');

    // Server config
    date_default_timezone_set('Europe/Madrid');
    ignore_user_abort(true);
    ini_set('memory_limit', '256M');
    // Start user session
    session_name(APP_NAME);
    session_start();
    
    // Error reporting
    if(ENVIRONMENT == 'PRE') {
    	error_reporting(E_ALL);
        ini_set('display_errors', '1');
    } else {
    	error_reporting(0);
        ini_set('display_errors', '0');
    }

    // If it is multilanguage
    if(MULTILANGUAGE == true) {
        // First I try to get the language from the route
        $lang = explode(PUBLIC_PATH.'/', ROUTE)[1];
        $lang = explode('/', $lang)[0];
        if(strpos($lang, '/') !== false) {
            $lang = explode('/', $lang)[0];
        }
        // Now try to get the language out of the vars
        if(!in_array($lang, LANGUAGES)) {
            if(isset($_COOKIE['lang'])) {
                $lang = $_COOKIE['lang'];
            } else if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            }
        }
        if(!in_array($lang, LANGUAGES)) {
            $lang = LANGUAGE;
        }
        // If the application is multilanguage and does not point to any language
        if(METHOD == 'get' && in_array(ROUTE, array(PUBLIC_PATH.'/', PUBLIC_PATH))) {
            header('Location: '.PUBLIC_PATH.'/'.$lang);
            exit;
        }
    } else {
        $lang = LANGUAGE;
    }
    // I check that the translations file exists
    if(!file_exists(LANG_PATH.'/'.$lang.'.php')) {
        $lang = LANGUAGE;
        if(!file_exists(LANG_PATH.'/'.$lang.'.php')) {
            echo 'The configuration file of the default language of the app does not exist. Check the <b>app/langs</b> folder.';
            exit;
        }
    }
    define('LANG', $lang);
    setcookie('lang', LANG, time() + (24 * 60 * 60 * 365), PUBLIC_PATH.'/'); // 1 año
    $_COOKIE['lang'] = LANG;
    include LANG_PATH.'/'.LANG.'.php';
    include LANG_PATH.'/routes.php';

    // Declare public paths
    if(MULTILANGUAGE == true) {
        define('VIEWS_PUBLIC', SERVER_PATH.'/app/views/public/'.LANG);
        define('PUBLIC_ROUTE', PUBLIC_PATH.'/'.LANG);
    } else {
        define('VIEWS_PUBLIC', SERVER_PATH.'/app/views/public');
        define('PUBLIC_ROUTE', PUBLIC_PATH);
    }
    define('URL', PROTOCOL.'://'.HOST.PUBLIC_ROUTE);

    // I check that it has the views folder of the translation
    if(!file_exists(VIEWS_PUBLIC)) {
        echo 'The public directory of the language views <b>'.VIEWS_PUBLIC.'</b> does not exist.';
        exit;
    }
    
    // If it is not in maintenance and try to access service-down view
    if(MAINTENANCE == false && ROUTE == PUBLIC_ROUTE.'/service-down') {
        header('Location: '.PUBLIC_ROUTE);
        exit;
    }

    // I save the application theme
    if(!isset($_COOKIE['color-mode'])) {
        setcookie('color-mode', 'light-mode', time() + (24 * 60 * 60 * 365), PUBLIC_PATH.'/'); // 1 año
        $_COOKIE['color-mode'] = 'light-mode';
    }

    // I connect to the database
    include SERVER_PATH.'/app/config/ddbb.php';
    $DB = new Ddbb();

?>