<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2023
     */

    // App Get
    $R->get(''                                      , 'CApp#root');
    $R->get('/'                                     , 'CApp#root');
    $R->get('/404'                                  , 'CApp#page_404', false);

    if(LANG == 'en') {
        $R->get('/home'                                 , 'CApp#home');
        $R->get('/privacy-policy'                       , 'CApp#privacy_policy');
        $R->get('/access'                               , 'CApp#access');
        $R->get('/register'                             , 'CApp#register');
        $R->get('/cart'                                 , 'CApp#cart');
        $R->get('/checkout'                             , 'CApp#checkout');
        $R->get('/service-down'                         , 'CApp#service_down', false);
        $R->get('/validate-email'                       , 'CApp#validate_email', false);
        $R->get('/logout'                               , 'CApp#logout', false);
    } else if(LANG == 'es') {
        $R->get('/inicio'                               , 'CApp#home');
        $R->get('/politica-de-privacidad'               , 'CApp#privacy_policy');
        $R->get('/acceso'                               , 'CApp#access');
        $R->get('/registro'                             , 'CApp#register');
        $R->get('/carrito'                              , 'CApp#cart');
        $R->get('/tramitar-pedido'                      , 'CApp#checkout');
        $R->get('/servicio-caido'                       , 'CApp#service_down', false);
        $R->get('/validar-email'                        , 'CApp#validate_email', false);
        $R->get('/desconectar'                          , 'CApp#logout', false);
    }

    $R->get_categories();
    $R->get_products();
    $R->get_categories_custom_routes();
    $R->get_products_custom_routes();

?>