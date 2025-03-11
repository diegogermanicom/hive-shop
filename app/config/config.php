<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2023
     */   
    
    // App name config
    define('APP_NAME', 'hive-shop');
    // Configuration of the admin name
    define('ADMIN_NAME', 'admin');

    // App hosts config
    define('HOST_DEV', strtolower('dev-germanicom.codeanyapp.com'));
    define('HOST_PRO', strtolower('nombredemitienda.com'));
    define('HOST', strtolower($_SERVER['HTTP_HOST']));

    // App default language
    define('LANGUAGE', 'en');
    // Set if the web is multilanguage. You should distribute your views in folders for each language
    // Example: /app/views/public/es
    define('MULTILANGUAGE', true);
    // Languages supported by the app
    define('LANGUAGES', array(LANGUAGE, 'es'));

    // Set if your website has a database
    define('HAS_DDBB', true);
    // Indicates whether the tables have a prefix
    define('DDBB_PREFIX', '');

    // App maintenance config
    define('MAINTENANCE', false);

    // Configure the data of the email sending server
    define('EMAIL_HOST', 'Hive Shop Framework');  
    define('EMAIL_FROM', 'info@hiveframework.com');

    // Default meta values for SEO
    define('META_TITLE', 'Hive Shop PHP Framework');
    define('META_DESCRIPTION', 'Welcome to Hive Shop, the fastest, lightest and simplest PHP framework for your web applications.');
    define('META_KEYS', 'Hive, framework, php');

    // Default Open Graph tags for rrss
    define('OG_TITLE', 'Hive Shop PHP Framework');
    define('OG_DESCRIPTION', 'Welcome to Hive Shop, the fastest, lightest and simplest PHP framework for your web applications.');
    define('OG_SITE_NAME', 'hiveframework.com');
    define('OG_TYPE', 'product');
    define('OG_URL', 'http://hiveframework.com');
    define('OG_IMAGE', 'http://hiveframework.com/img/website-logo.png');
    define('OG_APP_ID', '112651260921384');

    // I define Stripe tokens
    define('STRIPE_PRE', array(
        'secret_key' => '',
        'publishable_key' => ''
    ));
    define('STRIPE_PRO', array(
        'secret_key' => '',
        'publishable_key' => ''
    ));

    if(strpos(HOST, HOST_DEV) !== false) {
        define('ENVIRONMENT', 'PRE');
        define('PROTOCOL', 'https');
        define('PUBLIC_PATH', '/hive-shop');
        // MySQL config DEV
        define('DDBB_HOST', 'localhost');
        define('DDBB_USER', 'root');
        define('DDBB_PASS', '');
        define('DDBB', 'hive_shop');
    } else if(strpos(HOST, HOST_PRO) !== false) {
        define('ENVIRONMENT', 'PRO');
        define('PROTOCOL', 'https');
        define('PUBLIC_PATH', '');
        // MySQL config PRO
        define('DDBB_HOST', 'localhost');
        define('DDBB_USER', 'hive_shop');
        define('DDBB_PASS', 'Mysql_hive_shop-1');
        define('DDBB', 'hive_shop');
    } else {
        echo json_encode(array('error' => 'Permission denied.'));
        exit;
    }

?>