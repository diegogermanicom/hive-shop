<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2023
     */

    // App Get
    $R->setController('CApp');
    // Routes in English
    $R->get(
        [''                                 , 'root'                , 'en', 'empty-root'],
        ['/'                                , 'root'                , 'en', 'bar-root'],
        ['/404'                             , 'page_404'            , 'en', 'page-404'],
        ['/home'                            , 'home'                , 'en', 'home'],
        ['/privacy-policy'                  , 'privacy_policy'      , 'en', 'privacy-policy'],
        ['/cookie-policy'                   , 'privacy_policy'      , 'en', 'cookie-policy'],
        ['/access'                          , 'access'              , 'en', 'access'],
        ['/register'                        , 'register'            , 'en', 'register'],
        ['/cart'                            , 'cart'                , 'en', 'cart'],
        ['/checkout'                        , 'checkout'            , 'en', 'checkout'],
        ['/service-down'                    , 'service_down'        , 'en', 'service-down'],
        ['/validate-email'                  , 'validate_email'      , 'en', 'validate-email'],
        ['/logout'                          , 'logout'              , 'en', 'logout']
    );
    // Routes in Spanish
    $R->get(
        [''                                 , 'root'                , 'es', 'empty-root'],
        ['/'                                , 'root'                , 'es', 'bar-root'],
        ['/404'                             , 'page_404'            , 'es', 'page-404'],
        ['/inicio'                          , 'home'                , 'es', 'home'],
        ['/politica-de-privacidad'          , 'privacy_policy'      , 'es', 'privacy-policy'],
        ['/politica-de-cookies'             , 'privacy_policy'      , 'es', 'cookie-policy'],
        ['/acceso'                          , 'access'              , 'es', 'access'],
        ['/registro'                        , 'register'            , 'es', 'register'],
        ['/carrito'                         , 'cart'                , 'es', 'cart'],
        ['/tramitar-pedido'                 , 'checkout'            , 'es', 'checkout'],
        ['/servicio-caido'                  , 'service_down'        , 'es', 'service-down'],
        ['/validar-email'                   , 'validate_email'      , 'es', 'validate-email'],
        ['/desconectar'                     , 'logout'              , 'es', 'logout']
    );

    $R->get_categories();
    $R->get_products();
    $R->get_categories_custom_routes();
    $R->get_products_custom_routes();

?>