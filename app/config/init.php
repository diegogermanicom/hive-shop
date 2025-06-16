<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2025
    */

    require_once __DIR__.'/../libs/utils.php';

    $settings = require_once __DIR__.'/settings.php';
    // If all setting values are correct continue
    Utils::settingsValidator($settings);
 
    // Constant system variables
    define('HOST', strtolower($_SERVER['HTTP_HOST']));
    define('METHOD', strtolower($_SERVER['REQUEST_METHOD']));
    define('ROUTE', strtolower(strtok($_SERVER["REQUEST_URI"], '?')));

    define('HOST_DEV', $settings['HOST_DEV']);
    define('HOST_PRO', $settings['HOST_PRO']);
    define('ENVIRONMENT', Utils::getEnviroment());

    define('PROTOCOL', $settings[ENVIRONMENT]['PROTOCOL']);
    define('PUBLIC_PATH', $settings[ENVIRONMENT]['PUBLIC_PATH']);
    define('DDBB_HOST', $settings[ENVIRONMENT]['DDBB_HOST']);
    define('DDBB_USER', $settings[ENVIRONMENT]['DDBB_USER']);
    define('DDBB_PASS', $settings[ENVIRONMENT]['DDBB_PASS']);
    define('DDBB', $settings[ENVIRONMENT]['DDBB']);

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
    define('META_EXTRA_TITLE', $settings['META_EXTRA_TITLE']);
    define('META_DESCRIPTION', $settings['META_DESCRIPTION']);
    define('META_KEYS', $settings['META_KEYS']);

    define('OG_TITLE', $settings['OG_TITLE']);
    define('OG_DESCRIPTION', $settings['OG_DESCRIPTION']);
    define('OG_SITE_NAME', $settings['OG_SITE_NAME']);
    define('OG_TYPE', $settings['OG_TYPE']);
    define('OG_URL', $settings['OG_URL']);
    define('OG_IMAGE', $settings['OG_IMAGE']);
    define('OG_APP_ID', $settings['OG_APP_ID']);

    define('FTP_UPLOAD_HOST', $settings['FTP_UPLOAD_HOST']);
    define('FTP_UPLOAD_USER', $settings['FTP_UPLOAD_USER']);
    define('FTP_UPLOAD_PASS', $settings['FTP_UPLOAD_PASS']);
    define('FTP_UPLOAD_SERVER_PATH', $settings['FTP_UPLOAD_SERVER_PATH']);

    define('URL', PROTOCOL.'://'.HOST);
    define('SERVER_PATH', $_SERVER['DOCUMENT_ROOT'].PUBLIC_PATH);
    define('LIBS_PATH', SERVER_PATH.'/app/libs');
    define('CONTROLLERS_PATH', SERVER_PATH.'/app/controllers');
    define('MODELS_PATH', SERVER_PATH.'/app/models');
    define('ROUTES_PATH', SERVER_PATH.'/app/routes');
    define('LANG_PATH', SERVER_PATH.'/app/langs');
    define('IMG_PATH', SERVER_PATH.'/img');
    define('EMAILS_PATH', SERVER_PATH.'/app/emails');
    define('VIEWS_ADMIN', SERVER_PATH.'/app/views/admin');
    define('ADMIN_PATH', PUBLIC_PATH.'/'.ADMIN_NAME);
    
    // Find out the language
    define('LANG', Utils::getLanguage());
    require_once LANG_PATH.'/'.LANG.'.php';

    // Declare public paths
    if(MULTILANGUAGE == true) {
        define('VIEWS_PUBLIC', SERVER_PATH.'/app/views/public/'.LANG);
        define('PUBLIC_ROUTE', PUBLIC_PATH.'/'.LANG);
        // I check that it has the views folder of the translation
        if(!file_exists(VIEWS_PUBLIC)) {
            Utils::error('The public directory of the language views <b>'.VIEWS_PUBLIC.'</b> does not exist.');
        }
    } else {
        define('VIEWS_PUBLIC', SERVER_PATH.'/app/views/public');
        define('PUBLIC_ROUTE', PUBLIC_PATH);
    }
    define('URL_ROUTE', PROTOCOL.'://'.HOST.PUBLIC_ROUTE);
    
    Utils::init();
    Utils::checkServiceDownView();
    Utils::setThemeColor();

    require_once __DIR__.'/autoload-libs.php';
    require_once __DIR__.'/autoload-models.php';
    $controllers = require_once __DIR__.'/autoload-controllers.php';
    define('CONTROLLERS', $controllers);

    // I create the core objects
    $DB = new Ddbb();
    $R = new Route();

    require_once __DIR__.'/autoload-routes.php';
    define('ROUTES', $R->getRoutes());
    $R->init();

?>