<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2025
    */

    include __DIR__.'/../lib/utils.php';

    $settings = include __DIR__.'/settings.php';
    Utils::settingsValidator($settings);
    
    // If all setting values ​​are correct continue
    include __DIR__.'/../lib/ddbb.php';
    include __DIR__.'/../lib/controller.php';
    include __DIR__.'/../lib/model.php';
    include __DIR__.'/../lib/route.php';
    include __DIR__.'/../lib/ftp-upload.php';

    define('HOST_DEV', $settings['HOST_DEV']);
    define('HOST_PRO', $settings['HOST_PRO']);
    define('HOST', strtolower($_SERVER['HTTP_HOST']));

    if(strpos(HOST, HOST_DEV) !== false && HOST_DEV != '') {
        define('ENVIRONMENT', 'DEV');
        define('PROTOCOL', $settings['DEV']['PROTOCOL']);
        define('PUBLIC_PATH', $settings['DEV']['PUBLIC_PATH']);
        define('DDBB_HOST', $settings['DEV']['DDBB_HOST']);
        define('DDBB_USER', $settings['DEV']['DDBB_USER']);
        define('DDBB_PASS', $settings['DEV']['DDBB_PASS']);
        define('DDBB', $settings['DEV']['DDBB']);
       // Error reporting
       error_reporting(E_ALL);
        ini_set('display_errors', '1');
    } else if(strpos(HOST, HOST_PRO) !== false && HOST_PRO != '') {
        define('ENVIRONMENT', 'PRO');
        define('PROTOCOL', $settings['PRO']['PROTOCOL']);
        define('PUBLIC_PATH', $settings['PRO']['PUBLIC_PATH']);
        define('DDBB_HOST', $settings['PRO']['DDBB_HOST']);
        define('DDBB_USER', $settings['PRO']['DDBB_USER']);
        define('DDBB_PASS', $settings['PRO']['DDBB_PASS']);
        define('DDBB', $settings['PRO']['DDBB']);
       // Error reporting
    	error_reporting(0);
        ini_set('display_errors', '0');
    } else {
        echo json_encode(array('error' => 'Permission denied.'));
        exit;
    }

    define('APP_NAME', $settings['APP_NAME']);
    define('ADMIN_NAME', $settings['ADMIN_NAME']);

    define('LANGUAGE', $settings['LANGUAGE']);
    define('MULTILANGUAGE', $settings['MULTILANGUAGE']);
    define('LANGUAGES', $settings['LANGUAGES']);

    define('HAS_DDBB', $settings['HAS_DDBB']);
    define('DDBB_PREFIX', $settings['DDBB_PREFIX']);

    define('MAINTENANCE', $settings['MAINTENANCE']);
    define('MAINTENANCE_IPS', $settings['MAINTENANCE_IPS']);

    define('EMAIL_HOST', $settings['EMAIL_HOST']);
    define('EMAIL_FROM', $settings['EMAIL_FROM']);

    define('META_TITLE', $settings['META_TITLE']);
    define('META_DESCRIPTION', $settings['META_DESCRIPTION']);
    define('META_KEYS', $settings['META_KEYS']);

    define('OG_TITLE', $settings['OG_TITLE']);
    define('OG_DESCRIPTION', $settings['OG_DESCRIPTION']);
    define('OG_SITE_NAME', $settings['OG_SITE_NAME']);
    define('OG_TYPE', $settings['OG_TYPE']);
    define('OG_URL', $settings['OG_URL']);
    define('OG_IMAGE', $settings['OG_IMAGE']);
    define('OG_APP_ID', $settings['OG_APP_ID']);

    // App call method
    define('METHOD', strtolower($_SERVER['REQUEST_METHOD']));
    // App call route
    define('ROUTE', strtolower(strtok($_SERVER["REQUEST_URI"], '?')));

    define('SERVER_PATH', $_SERVER['DOCUMENT_ROOT'].PUBLIC_PATH);
    define('LIB_PATH', SERVER_PATH.'/app/lib');
    define('CONTROLLERS_PATH', SERVER_PATH.'/app/controllers');
    define('MODELS_PATH', SERVER_PATH.'/app/models');
    define('ROUTES_PATH', SERVER_PATH.'/app/routes');
    define('LANG_PATH', SERVER_PATH.'/app/langs');
    define('IMG_PATH', SERVER_PATH.'/img');
    define('EMAILS_PATH', SERVER_PATH.'/app/emails');
    define('VIEWS_ADMIN', SERVER_PATH.'/app/views/admin');
    define('ADMIN_PATH', PUBLIC_PATH.'/'.ADMIN_NAME);

    // Server config
    date_default_timezone_set('Europe/Madrid');
    ignore_user_abort(true);
    ini_set('memory_limit', '256M');
    // Start user session
    session_name(APP_NAME);
    session_start();
    
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
            exit('The configuration file of the default language of the app does not exist. Check the <b>app/langs</b> folder.');
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
        exit('The public directory of the language views <b>'.VIEWS_PUBLIC.'</b> does not exist.');
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
    $DB = new Ddbb();

?>