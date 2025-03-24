<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2023
     */

    // App Post
    $R->setController('CAppAjax');
    $R->post('/set-cookies'                         , 'set_cookies');
    $R->post('/save-newsletter'                     , 'save_newsletter');
    $R->post('/login-send'                          , 'login_send');
    $R->post('/register-send'                       , 'register_send');
    $R->post('/choose-language'                     , 'choose_language');
    $R->post('/choose-color-mode'                   , 'choose_color_mode');
    $R->post('/get-product-related'                 , 'get_product_related');
    $R->post('/add-cart'                            , 'add_cart');
    $R->post('/notify-stock'                        , 'notify_stock');
    $R->post('/send-notify-stock'                   , 'send_notify_stock');
    $R->post('/get-popup-cart'                      , 'get_popup_cart');
    $R->post('/remove-cart-product'                 , 'remove_cart_product');
    $R->post('/change-product-amount'               , 'change_product_amount');
    $R->post('/get-addresses'                       , 'get_addresses');
    $R->post('/save-new-address'                    , 'save_new_address');
    $R->post('/get-address'                         , 'get_address');
    $R->post('/delete-address'                      , 'delete_address');
    $R->post('/save-edit-address'                   , 'save_edit_address');
    $R->post('/get-billing-addresses'               , 'get_billing_addresses');
    $R->post('/save-new-billing-address'            , 'save_new_billing_address');
    $R->post('/get-billing-address'                 , 'get_billing_address');
    $R->post('/delete-billing-address'              , 'delete_billing_address');
    $R->post('/save-edit-billing-address'           , 'save_edit_billing_address');    
    $R->post('/get-shipping-methods'                , 'get_shipping_methods');
    $R->post('/apply-code'                          , 'apply_code');
    $R->post('/save-order-to-cart'                  , 'save_order_to_cart');
    $R->post('/get-countries-list'                  , 'get_countries_list');
    $R->post('/get-provinces-list'                  , 'get_provinces_list');
    
?>