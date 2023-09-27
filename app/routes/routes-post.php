<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2023
     */

    // App Post
    $R->post('/set-cookies'                         , 'CAppAjax#set_cookies');
    $R->post('/save-newsletter'                     , 'CAppAjax#save_newsletter');
    $R->post('/login-send'                          , 'CAppAjax#login_send');
    $R->post('/register-send'                       , 'CAppAjax#register_send');
    $R->post('/choose-language'                     , 'CAppAjax#choose_language');
    $R->post('/choose-color-mode'                   , 'CAppAjax#choose_color_mode');
    $R->post('/get-product-related'                 , 'CAppAjax#get_product_related');
    $R->post('/add-cart'                            , 'CAppAjax#add_cart');
    $R->post('/notify-stock'                        , 'CAppAjax#notify_stock');
    $R->post('/send-notify-stock'                   , 'CAppAjax#send_notify_stock');
    $R->post('/get-popup-cart'                      , 'CAppAjax#get_popup_cart');
    $R->post('/remove-cart-product'                 , 'CAppAjax#remove_cart_product');
    $R->post('/change-product-amount'               , 'CAppAjax#change_product_amount');
    $R->post('/get-address'                         , 'CAppAjax#get_address');
    $R->post('/save-new-address'                    , 'CAppAjax#save_new_address');
    $R->post('/get-countries-list'                  , 'CAppAjax#get_countries_list');
    $R->post('/get-provinces-list'                  , 'CAppAjax#get_provinces_list');
    
?>