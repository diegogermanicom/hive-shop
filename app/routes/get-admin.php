<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2023
     */

    // Admin Get
    $R->setController('CAdmin');
    $R->getAdmin(''                                 , 'root');
    $R->getAdmin('/'                                , 'root');
    $R->getAdmin('/login'                           , 'login');
    $R->getAdmin('/logout'                          , 'logout');
    $R->getAdmin('/home'                            , 'home');

    $R->getAdmin('/products'                        , 'products');
    $R->getAdmin('/products-custom-routes'          , 'products_custom_routes');
    $R->getAdmin('/new-product'                     , 'new_product');
    $R->getAdmin('/edit-product'                    , 'edit_product');

    $R->getAdmin('/categories'                      , 'categories');
    $R->getAdmin('/categories-custom-routes'        , 'categories_custom_routes');
    $R->getAdmin('/new-category'                    , 'new_category');
    $R->getAdmin('/edit-category'                   , 'edit_category');
    
    $R->getAdmin('/attributes'                      , 'attributes');
    $R->getAdmin('/new-attribute'                   , 'new_attribute');
    $R->getAdmin('/edit-attribute'                  , 'edit_attribute');

    $R->getAdmin('/images'                          , 'images');
    $R->getAdmin('/codes'                           , 'codes');
    $R->getAdmin('/new-code'                        , 'new_code');
    $R->getAdmin('/edit-code'                       , 'edit_code');
    $R->getAdmin('/stats'                           , 'stats');
    $R->getAdmin('/languages'                       , 'languages');
    $R->getAdmin('/edit-language'                   , 'edit_language');
    $R->getAdmin('/users'                           , 'users');
    $R->getAdmin('/edit-user'                       , 'edit_user');
    $R->getAdmin('/users-admin'                     , 'users_admin');
    $R->getAdmin('/edit-user-admin'                 , 'edit_user_admin');
    $R->getAdmin('/new-admin-user'                  , 'new_admin_user');
    $R->getAdmin('/carts'                           , 'carts');
    $R->getAdmin('/view-cart'                       , 'view_cart');
    $R->getAdmin('/orders'                          , 'orders');
    $R->getAdmin('/order'                           , 'order');
    $R->getAdmin('/taxes'                           , 'taxes');
    $R->getAdmin('/locations'                       , 'locations');

    $R->getAdmin('/shipments'                       , 'shipments');
    $R->getAdmin('/new-shipping-method'             , 'new_shipping_method');
    $R->getAdmin('/edit-shipping-method'            , 'edit_shipping_method');
    $R->getAdmin('/shipping-zones'                  , 'shipping_zones');
    $R->getAdmin('/new-shipping-zone'               , 'new_shipping_zone');
    $R->getAdmin('/edit-shipping-zone'              , 'edit_shipping_zone');

    $R->getAdmin('/payments'                        , 'payments');
    $R->getAdmin('/new-payment-method'              , 'new_payment_method');
    $R->getAdmin('/edit-payment-method'             , 'edit_payment_method');
    $R->getAdmin('/payment-zones'                   , 'payment_zones');
    $R->getAdmin('/new-payment-zone'                , 'new_payment_zone');
    $R->getAdmin('/edit-payment-zone'               , 'edit_payment_zone');
    
    $R->getAdmin('/ftp-upload'                      , 'ftp_upload');

?>