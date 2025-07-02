<?php

    /**
     * @author Diego Martín
     * @copyright Hive®
     * @version 1.0
     * @lastUpdated 2025
     */

    return array(
        // Internal name of the app (slug format)
        'APP_NAME' => 'hive-shop',
        // Setting the administrator access path (slug format)
        'ADMIN_NAME' => 'admin',
        // App hosts config (domain format)
        'HOST_DEV' => 'dev-germanicom.codeanyapp.com',
        'HOST_PRO' => 'nombredemitienda.com',
        // App default language (ISO language codes)
        'LANGUAGE' => 'en',
        // Set if the web is multilanguage.
        'MULTILANGUAGE' => true,
        // Languages supported by the application if it is multilingual (ISO language codes)
        'LANGUAGES' => array('en', 'es'),
        // Set if your website has a database
        'HAS_DDBB' => true,
        // Indicates whether the tables have a prefix (without spaces)
        'DDBB_PREFIX' => '',
        // App maintenance config
        'MAINTENANCE' => false,
        // Indicates the IPs that will have access in maintenance mode
        'MAINTENANCE_IPS' => array(),
        // Configure the data of the email sending server
        'EMAIL_HOST' => 'hiveframework.com',
        'EMAIL_FROM' => 'info@hiveframework.com',
        // Default meta values for SEO
        'META_TITLE' => 'Hive Shop PHP Framework',
        'META_EXTRA_TITLE' => ' | Hive Shop',
        'META_DESCRIPTION' => 'Welcome to Hive Shop, the fastest, lightest and simplest PHP framework for your web applications.',
        'META_KEYS' => 'hive shop, framework, php',
        // Default Open Graph tags for RRSS
        'OG_TITLE' => 'Hive Shop PHP Framework',
        'OG_DESCRIPTION' => 'Welcome to Hive Shop, the fastest, lightest and simplest PHP framework for your web applications.',
        'OG_SITE_NAME' => 'hiveframework.com',
        'OG_TYPE' => 'product',
        'OG_URL' => 'https://hiveframework.com/home',
        'OG_IMAGE' => 'https://hiveframework.com/img/website-logo.png',
        'OG_APP_ID' => '112651260921384',
        'DEV' => array(
            'PROTOCOL' => 'https',
            // Leave empty to indicate the root
            'PUBLIC_PATH' => '/hive-shop',
            // Database Configuration
            'DDBB_HOST' => 'localhost',
            'DDBB_USER' => 'root',
            'DDBB_PASS' => '',
            'DDBB' => 'hive_shop'
        ),
        'PRO' => array(
            'PROTOCOL' => 'https',
            // Leave empty to indicate the root
            'PUBLIC_PATH' => '',
            // Database Configuration
            'DDBB_HOST' => 'localhost',
            'DDBB_USER' => 'hiveuser',
            'DDBB_PASS' => 'mysqlhivepass',
            'DDBB' => 'hive_shop'
        ),
        'FTP_UPLOAD_HOST' => '',
        'FTP_UPLOAD_USER' => '',
        'FTP_UPLOAD_PASS' => '',
        // Leave empty to indicate the root
        'FTP_UPLOAD_SERVER_PATH' => ''
    );

?>