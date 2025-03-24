<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2023
     */

    // App Get
    $R->setController('CApp');
    $R->get(''                                      , 'root');
    $R->get('/'                                     , 'root');
    $R->get('/404'                                  , 'page_404', false);

    if(LANG == 'en') {
        $R->get('/home'                                 , 'home');
        $R->get('/privacy-policy'                       , 'privacy_policy');
        $R->get('/access'                               , 'access');
        $R->get('/register'                             , 'register');
        $R->get('/cart'                                 , 'cart');
        $R->get('/checkout'                             , 'checkout');
        $R->get('/service-down'                         , 'service_down', false);
        $R->get('/validate-email'                       , 'validate_email', false);
        $R->get('/logout'                               , 'logout', false);
    } else if(LANG == 'es') {
        $R->get('/inicio'                               , 'home');
        $R->get('/politica-de-privacidad'               , 'privacy_policy');
        $R->get('/acceso'                               , 'access');
        $R->get('/registro'                             , 'register');
        $R->get('/carrito'                              , 'cart');
        $R->get('/tramitar-pedido'                      , 'checkout');
        $R->get('/servicio-caido'                       , 'service_down', false);
        $R->get('/validar-email'                        , 'validate_email', false);
        $R->get('/desconectar'                          , 'logout', false);
    }

    $R->get_categories();
    $R->get_products();
    $R->get_categories_custom_routes();
    $R->get_products_custom_routes();

?>